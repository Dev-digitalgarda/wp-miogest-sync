<?php

namespace WpMiogestSync\Modules;

use WpMiogestSync\Utils\Logger;
use WpMiogestSync\Utils\EraseThumbnails;

use Webmozart\PathUtil\Path;

class Syncer
{
    private static $allowed_addrs = ['77.39.210.53', '127.0.0.1', '151.84.171.207'];
    private static string $static_annunci_table = 'miogest_synced_annunci';

    private string $base_post_url;
    private string $thumbnails_prefix;
    private string $upload_dir_path;
    private string $now;
    private array $langs;
    private array $floors;

    private array $annunci;
    private array $categorie;
    private array $stati_immobili;

    private string $annunci_table;
    private string $posts_table;
    private string $postmeta_table;
    private string $term_relationships_table;
    private string $icl_translations_table;
    public array $miogest_sync_annunci_ids;

    private function getFotosFromAnnuncio(array $annuncio): ?string
    {
        $fotos_str = null;

        if (array_key_exists('Foto', $annuncio) && is_array($annuncio['Foto'])) {
            $fotos = array_map(function ($foto) {
                return end(explode('/', $foto));
            }, $annuncio['Foto']);
            $fotos_str = implode(',', $fotos);
        }

        return $fotos_str;
    }

    private function addThumbnailToAnnuncio(array $post_ids, array $annuncio): void
    {
        if (array_key_exists('Foto', $annuncio) && is_array($annuncio['Foto'])) {
            $image_url = array_shift($annuncio['Foto']);

            foreach ($post_ids as $post_id) {
                $filename = "{$this->thumbnails_prefix}{$post_id}.jpg";
                $filepath = $this->downloadFotoAndCrop($image_url, $filename);

                $upload_file = wp_upload_bits($filename, null, @file_get_contents($filepath));
                if (!$upload_file['error']) {
                    $wp_filetype = wp_check_filetype($filename, null);
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_parent'    => $post_id,
                        'post_title'     => preg_replace('/\.[^.]+$/', '', $filename),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );
                    $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $post_id);
                    if (!is_wp_error($attachment_id)) {

                        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
                        wp_update_attachment_metadata($attachment_id,  $attachment_data);
                        set_post_thumbnail($post_id, $attachment_id);

                        $old_gallery_value = get_post_meta($post_id, 'property_gallery', true);
                        $new_gallery_fotos = explode(',', $old_gallery_value);
                        array_unshift($new_gallery_fotos, $attachment_id);
                        update_post_meta($post_id, 'property_gallery', implode(',', $new_gallery_fotos));
                    }
                }
            }
        }
    }

    private function getPostMeta(array $annuncio, string $lang): array
    {
        return [
            'locality' => strtolower(str_replace(' ', '', $annuncio['Comune'])),
            'property_gallery' => $this->getFotosFromAnnuncio($annuncio),
            'property_price' => !is_array($annuncio['Prezzo']) ? $annuncio['Prezzo'] : '',
            'property_price_reserved' => $annuncio['Prezzo_Riservato'] == '1' ? 'true' : 'false',
            'built_in' => !is_array($annuncio['Anno']) ? $annuncio['Anno'] : '',
            'classe_energetica' => !is_array($annuncio['Classe']) ? $annuncio['Classe'] : '',
            'stato_immobile' => !is_array($annuncio['Scheda_StatoImmobile'])
                ? $this->stati_immobili['valori'][$annuncio['Scheda_StatoImmobile']][$lang]
                : '',
            'piano' => !is_array($annuncio['Piano']) ? $this->floors[$lang][$annuncio['Piano']] : '',
            'codice' => !is_array($annuncio['Codice']) ? $annuncio['Codice'] : '',
            'property_size' => !is_array($annuncio['Mq']) ? $annuncio['Mq'] : '',
            'mq_sotto' => !is_array($annuncio['Mq']) ? $annuncio['Mq'] : '',
            'property_agent' => '290',
            'property_featured' => '1' // Note: in old code it was 1 if annuncio_index < 4
        ];
    }

    private function insertAnnuncio(int $annuncio_id, int $post_id, string $lang): void
    {
        global $wpdb;

        $wpdb->insert(
            $this->annunci_table,
            [
                'post_id' => $post_id,
                'type' => '1',
                'annuncio_id' => $annuncio_id,
                'lang' => $lang
            ]
        );
    }

    private function insertPost(string $descrizione, string $titolo, string $name, array $meta_info): int
    {
        $post = [
            'post_content' => $descrizione,
            'post_title' => $titolo,
            'post_name' => $name,
            'post_type' => 'property',
            'post_date' => $this->now,
            'post_date_gmt' => $this->now,
            'post_modified' => $this->now,
            'post_modified_gmt' => $this->now,
            'comment_status' => 'closed',
            'post_status' => 'publish',
            'ping_status' => 'closed',
            'meta_input' => $meta_info
        ];

        $id = wp_insert_post($post);
        wp_update_post(['ID' => $id, 'guid' => $this->base_post_url . $id]);

        return $id;
    }

    private function insertTranslationBinding(array $ids_mapped_by_lang): void
    {
        global $wpdb;

        $next_translation_id = 1 + $wpdb->get_var("SELECT MAX(trid) FROM $this->icl_translations_table");
        foreach ($this->langs as $lang) {
            $wpdb->insert(
                $this->icl_translations_table,
                [
                    'trid' => $next_translation_id,
                    'language_code' => $lang,
                    'source_language_code' => 'it',
                    'element_type' => 'post_property',
                    'element_id' => $ids_mapped_by_lang[$lang]
                ]
            );

            if ($wpdb->insert_id == 0) {
                $wpdb->update(
                    $this->icl_translations_table,
                    [
                        'trid' => $next_translation_id,
                        'language_code' => $lang,
                        'source_language_code' => 'it',
                        'element_type' => 'post_property'
                    ],
                    [
                        'element_id' => $ids_mapped_by_lang[$lang]
                    ]
                );
            }
        }
    }

    private function insertRelationships(array $annuncio, array $record_ids): void
    {
        global $wpdb;

        // I have no idea of what this does
        $arr_cat_miogest = [17, 18, 25, 26, 28, 119, 50, 30, 32, 87, 44, 46, 91, 127, 117, 48, 84, 34, 105, 123, 125, 83, 95, 33, 93, 97, 40, 41, 42, 85, 99, 36, 82, 81, 86, 1];
        $keys = [11];

        if (array_key_exists('Categoria', $annuncio)) {
            $categorie = $annuncio['Categoria'];
            if (!is_array($categorie)) {
                $categorie = [$categorie];
            }

            foreach ($categorie as $categoria) {
                $key = 21 + intval(array_search($categoria, $arr_cat_miogest));
                array_push($keys, $key);
            }
        }

        foreach ($keys as $key) {
            foreach ($record_ids as $record_id) {
                $wpdb->insert(
                    $this->term_relationships_table,
                    [
                        'object_id' => $record_id,
                        'term_taxonomy_id' => $key,
                        'term_order' => 0
                    ]
                );
            }
        }
    }

    private function downloadFotoAndCrop(string $img_url, string $file_name): string
    {
        $filepath = $this->upload_dir_path . '/' . $file_name;
        $contents = file_get_contents($img_url);
        $savefile = fopen($filepath, 'w');
        fwrite($savefile, $contents);
        fclose($savefile);

        $img_editor = wp_get_image_editor($filepath);
        if (!is_wp_error($img_editor)) {
            $img_editor->resize(400, 400, true);
            $img_editor->save($filepath);
        }

        return $filepath;
    }

    public function __construct()
    {
        global $table_prefix;

        $this->base_post_url = get_site_url() . '/prova/?post_type=property&p=';
        $this->thumbnails_prefix = 'miogest_sync_';
        $this->now = current_time('mysql', true);
        $this->upload_dir_path = wp_upload_dir()['path'];
        $this->langs = ["it", "en", 'de'];
        $this->floors = [
            'it' => ['Piano terra', 'Primo piano', 'Secondo piano', 'Terzo piano', 'Quarto piano', 'Quinto piano'],
            'en' => ['Ground Floor', 'First Floor', 'Second Floor', 'Third Floor', 'Fourth Floor', 'Fifth Floor'],
            'de' => ['Erdgeschoss', 'Erster Stock', 'Zweiter Stock', 'Dritter Stock', 'Vierter Stock', 'Fünfter Stock']
        ];

        $this->annunci_table = $table_prefix . Syncer::$static_annunci_table;
        $this->posts_table = $table_prefix . 'posts';
        $this->postmeta_table = $table_prefix . 'postmeta';
        $this->term_relationships_table = $table_prefix . 'term_relationships';
        $this->icl_translations_table = $table_prefix . 'icl_translations';

        Logger::$logger->debug('Base post url is ', ['base_post_url' => $this->base_post_url]);
        Logger::$logger->debug('Upload dir path is ', ['upload_dir_path' => $this->upload_dir_path]);
    }

    public function createTableIfNotExists(): void
    {
        global $wpdb;

        if ($wpdb->get_var("SHOW TABLS LIKE '$this->annunci_table'") != $this->annunci_table) {

            $sql = "CREATE TABLE $this->annunci_table ( 
              `post_id` int(11) NOT NULL, 
              `type` int(2) NOT NULL,
              `annuncio_id` int(11) NOT NULL,
              `lang` CHAR(2) NOT NULL,
              PRIMARY KEY `order_id` (`post_id`, `type`) 
            ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
            dbDelta($sql);
        }
    }

    public function checkRemoteAddress(): void
    {
        $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        if (!in_array($remote_addr, Syncer::$allowed_addrs)) {
            Logger::$logger->warning('Invalid remote address', ['remote_addr' => $remote_addr]);
            die("Remote address $remote_addr not allowed");
        }
    }

    public function fetchRemoteData(): void
    {
        Logger::$logger->debug('Fetching annunci');
        $urlAnnunci = "http://partner.miogest.com/agenzie/gardahomeproject.xml";
        $xmlAnnunci = simplexml_load_file($urlAnnunci);
        $jsonAnnunci = json_encode($xmlAnnunci);
        $annunci = json_decode($jsonAnnunci, true);
        $this->annunci = $annunci['Annuncio'];

        Logger::$logger->debug('Fetching categorie');
        $urlCategorie = "http://www.miogest.com/apps/revo.aspx?tipo=categorie";
        $xmlCategorie = simplexml_load_file($urlCategorie);
        $jsonCategorie = json_encode($xmlCategorie);
        $categorie = json_decode($jsonCategorie, true);
        $this->categorie = $categorie['cat'];

        Logger::$logger->debug('Fetching stati immobili');
        $urlSchedeImmobili = "http://www.miogest.com/apps/revo.aspx?tipo=schede";
        $xmlSchedeImmobili = simplexml_load_file($urlSchedeImmobili);
        $jsonSchedeImmobili = json_encode($xmlSchedeImmobili);
        $schedeImmobili = json_decode($jsonSchedeImmobili, true)['scheda'];
        $schedaStatiImmobili = array_values(
            array_filter($schedeImmobili, function ($scheda) {
                return $scheda['id'] == '57';
            })
        )[0];
        $stati_immobili = [
            'nomi' => [
                'it' => $schedaStatiImmobili['nome'],
                'en' => $schedaStatiImmobili['nome_en'],
                'de' => $schedaStatiImmobili['nome_de']
            ],
            'valori' => []
        ];
        $stati_immobili_valori = $schedaStatiImmobili['valori']['valore'];
        for ($i = 0; $i < count($stati_immobili_valori); $i++) {
            $value = $stati_immobili_valori[$i];
            $stati_immobili['valori'][$value] = [
                'it' => $schedaStatiImmobili['valori']['valore'][$i],
                'en' => $schedaStatiImmobili['valori_en']['valore'][$i],
                'de' => $schedaStatiImmobili['valori_de']['valore'][$i]
            ];
        }
        $this->stati_immobili = $stati_immobili;
    }

    public function getAnnunciIds(): void
    {
        global $wpdb;

        $result = $wpdb->get_results("
            SELECT ID 
            FROM $this->posts_table 
            WHERE post_type = 'property'
        ");

        $this->miogest_sync_annunci_ids = array_map(function ($item) {
            return $item->ID;
        }, $result);
    }

    public function deleteOldAnnunci(): void
    {
        global $wpdb;

        $annunci_count = count($this->miogest_sync_annunci_ids);
        $placeholders = array_fill(0, $annunci_count, '%d');
        $format = implode(', ', $placeholders);

        function getQuery(string $table, string $prop, string $format): string
        {
            return "
                DELETE FROM $table
                WHERE $prop IN ($format)
            ";
        }
        $annunci_table_query = "DELETE FROM $this->annunci_table";
        $post_thumbnails_query = "
            DELETE FROM $this->posts_table
            WHERE post_name LIKE 'miogest_sync_%'
        ";

        $queries = [
            getQuery($this->annunci_table, 'post_id', $format),
            getQuery($this->posts_table, 'ID', $format),
            getQuery($this->postmeta_table, 'post_id', $format),
            getQuery($this->term_relationships_table, 'object_id', $format),
            getQuery($this->icl_translations_table, 'element_id', $format),
            $annunci_table_query,
            $post_thumbnails_query
        ];

        foreach ($queries as $query) {
            $wpdb->query($wpdb->prepare($query, $this->miogest_sync_annunci_ids));
        }
    }

    public function deleteOldAnnunciThumbs(): void
    {
        $path = Path::join($this->upload_dir_path, '..', '..');
        $eraser = new EraseThumbnails($path, $this->thumbnails_prefix);
        $eraser->erase();
    }

    public function insertNewAnnunci(): void
    {
        foreach ($this->annunci as $annuncio) {
            $id = $annuncio['AnnuncioId'];
            Logger::$logger->debug('Managing annuncio with id', ['id' => $id]);

            Logger::$logger->debug('Getting main annuncio info');

            // If the description is missing, let it as a default string
            $default_descrizione_it = 'Descrizione mancante';
            $default_descrizione_en = 'Description missing';
            $default_descrizione_de = 'Fehlende Beschreibung';

            $descrizione_it = !is_array($annuncio['Descrizione']) ? $annuncio['Descrizione'] : $default_descrizione_it;
            // If it is missing in this language, try the Italian one or use the default for that language
            $descrizione_en = !is_array($annuncio['Descrizione_En'])
                ? $annuncio['Descrizione_En']
                : ($descrizione_it != $default_descrizione_it ? $descrizione_it : $default_descrizione_en);
            // If it is missing in this language, try the Italian one or use the default for that language
            $descrizione_de = !is_array($annuncio['Descrizione_De'])
                ? $annuncio['Descrizione_De']
                : ($descrizione_it != $default_descrizione_it ? $descrizione_it : $default_descrizione_de);

            // If the title is missing and there is no default, put the id with the national prefix
            $titolo_it = !is_array($annuncio['Titolo']) ? $annuncio['Titolo'] : $id . '_IT';
            $titolo_en = !is_array($annuncio['Titolo_En'])
                ? $annuncio['Titolo_En']
                : ($titolo_it != $id . '_IT' ? $titolo_it : $id . '_EN');
            $titolo_de = !is_array($annuncio['Titolo_De'])
                ? $annuncio['Titolo_De']
                : ($titolo_it != $id . '_IT' ? $titolo_it : $id . '_DE');

            // The name is the titolo with lowercase and underscores
            $name_it = preg_replace("/[^A-Za-z0-9_]/", '_', strtolower(str_replace(' ', '_', $titolo_it . '_' . $id)));
            $name_en = preg_replace("/[^A-Za-z0-9_]/", '_', strtolower(str_replace(' ', '_', $titolo_en . '_' . $id)));
            $name_de = preg_replace("/[^A-Za-z0-9_]/", '_', strtolower(str_replace(' ', '_', $titolo_de . '_' . $id)));

            // Add metas
            Logger::$logger->debug('Getting annuncio metainfo');
            $meta_info_it = $this->getPostMeta($annuncio, 'it');
            $meta_info_en = $this->getPostMeta($annuncio, 'en');
            $meta_info_de = $this->getPostMeta($annuncio, 'de');

            // Inserts the posts
            Logger::$logger->debug('Inserting posts');
            $post_id_it = $this->insertPost($descrizione_it, $titolo_it, $name_it, $meta_info_it);
            $post_id_en = $this->insertPost($descrizione_en, $titolo_en, $name_en, $meta_info_en);
            $post_id_de = $this->insertPost($descrizione_de, $titolo_de, $name_de, $meta_info_de);

            // Insert the annunci in the list
            Logger::$logger->debug('Inserting annunci on annunci table');
            $this->insertAnnuncio($id, $post_id_it, 'it');
            $this->insertAnnuncio($id, $post_id_en, 'en');
            $this->insertAnnuncio($id, $post_id_de, 'de');

            // Insert relationships
            Logger::$logger->debug('Inserting relationships with taxonomy');
            $this->insertRelationships($annuncio, [$post_id_it, $post_id_en, $post_id_de]);

            // Connect translations 
            Logger::$logger->debug('Inserting translation bindings');
            $this->insertTranslationBinding(['it' => $post_id_it, 'en' => $post_id_en, 'de' => $post_id_de]);

            // Add thumbnail
            Logger::$logger->debug('Adding thumbnails');
            $this->addThumbnailToAnnuncio([$post_id_it, $post_id_en, $post_id_de], $annuncio);
        }
    }
}
