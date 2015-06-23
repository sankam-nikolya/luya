<?php

namespace cmsadmin\blocks;

use Yii;

class FileListBlock extends \cmsadmin\base\Block
{
    public $module = 'cmsadmin';

    public function name()
    {
        return 'Dateien';
    }

    public function icon()
    {
        return "mdi-file-file-download";
    }

    /**
     * @todo enabling display of file size when issue #94 is resolved
     */
    public function config()
    {
        return [
            'vars' => [
                ['var' => 'files', 'label' => 'Dateien', 'type' => 'zaa-file-array-upload'],
            ],
            'cfgs' => [
                ['var' => 'showType', 'label' => 'Dateityp anzeigen?', 'type' => 'zaa-select', 'options' =>
                    [
                        ['value' => '1', 'label' => 'Ja'],
                        ['value' => '0', 'label' => 'Nein'],
                    ],
                ],
                /*
                ['var' => 'showSize', 'label' => 'Dateigrösse anzeigen?', 'type' => 'zaa-select', 'options' =>
                    [
                        ['value' => '0', 'label' => 'Ja'],
                        ['value' => '1', 'label' => 'Nein'],
                    ],
                ],
                */
            ],
        ];
    }

    public function extraVars()
    {
        $fileEntries = $this->getVarValue('files');
        $files = [];

        if(!empty($fileEntries)) {
            foreach($fileEntries as $fileEntry) {

                if (array_key_exists("fileId", $fileEntry)) {
                    $files[] = [
                        "meta" => $fileEntry,
                        "file" => Yii::$app->storage->file->get($fileEntry["fileId"])
                    ];
                }

            }
        }

        /*
         * filesize:
         *
         * $file = get_headers($file['file']['source_http'], 1);
         * $file["Content-Length"];
         *
         */


        return [
            'fileList' => $files
        ];
    }

    public function twigFrontend()
    {
        return '{% if extras.fileList is not empty %}<ul>{% for fileEntry in extras.fileList %}<li><a href="{{ fileEntry.file.source_http }}">{{ fileEntry.meta.caption }}{% if cfgs.showType %} ({{ fileEntry.file.extension }}){% endif %}</a></li>{% endfor %}</ul>{% endif %}';
    }

    public function twigAdmin()
    {
        return '{% if extras.fileList is empty %}<span class="block__empty-text">Es wurden noch keine Dateien angegeben.</span>{% else %}<ul>{% for fileEntry in extras.fileList %}<li><a href="{{ fileEntry.file.source_http }}">{{ fileEntry.meta.caption }}{% if cfgs.showType == 1 %} ({{ fileEntry.file.extension }}){% endif %}</a></li>{% endfor %}</ul>{% endif %}';
    }
}
