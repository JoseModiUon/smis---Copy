<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\helpers;

use JetBrains\PhpStorm\ArrayShape;
use Yii;

/**
 * Configure various grid export settings.
 * Call this class on a gridview you wish to export data from.
 */
class GridExport
{
    /**
     * Set pdf export header
     * 
     * @param string $centerContent
     * 
     * @return array [pdf header config]
     */
    #[ArrayShape(['L' => "array", 'C' => "array", 'R' => "array", 'line' => "bool"])]
    private static function setHeader(string $centerContent):array{
        return [
            'L'    => [
                'content' => 'NATIONAL DEFENCE UNIVERSITY - KENYA',
                'font-size'   => 10,
                'font-style'  => 'B',
                'font-family' => 'arial',
                'color'       => '#333333',
            ],
            'C'    => [
                'content'     => $centerContent,
                'font-size'   => 10,
                'font-style'  => 'B',
                'font-family' => 'arial',
                'color'       => '#333333',
            ],
            'R'    => [
                'content' => 'Generated on '.date('d-M-y'),
                'font-size'   => 10,
                'font-family' => 'arial',
                'color'       => '#333333',
            ],
            'line' => true,
        ];
    }

    /**
     * Set pdf export footer
     * 
     * @return array [pdf footer config]
     */
    #[ArrayShape(['L' => "array", 'C' => "string[]", 'R' => "array", 'line' => "bool"])]
    private static function setFooter():array
    {
        $user = Yii::$app->user->identity->username;

        return [
            'L' => [
                'content'   =>  'Generated by ' . $user,
                'font-size'   => 10,
                'font-family' => 'arial',
                'color'       => '#333333',
            ],
            'C' => [
                'content'   =>  '',
            ],
            'R' => [
                'content'       => 'Page [{PAGENO}]',
                'font-size'     => 10,
                'color'         => '#333333',
                'font-family'   => 'arial',
            ],
            'line' => true,
       ];
    }

    /**
     * Configure Excel export 
     * 
     * @param array $config
     * 
     * @return array [Excel configs]
     */
    #[ArrayShape(['label' => "string", 'showHeader' => "bool", 'showPageSummary' => "false", 'showFooter' => "false", 'showCaption' => "false", 'filename' => "mixed", 'alertMsg' => "string", 'mime' => "string", 'config' => "array"])]
    public static function exportExcel(array $config):array
    {
        return [
            'label' => 'EXCEL',
            'showHeader' => true,
            'showPageSummary' => false,
            'showFooter' => false,
            'showCaption' => false,
            'filename' => $config['filename'],
            'alertMsg' => 'The Excel export file will be generated for download.',    
            'mime' => 'application/vnd.ms-excel',
            'config' => [
                'worksheet' => $config['worksheet'],
                'cssFile' => ''
            ]
        ];
    }

    /**
     * Configure PDF export
     * 
     * @param array $config
     * 
     * @return array [PDF configs]
     */
    #[ArrayShape(['label' => "string", 'filename' => "mixed", 'showHeader' => "bool", 'showPageSummary' => "false", 'showFooter' => "false", 'showCaption' => "false", 'alertMsg' => "string", 'mime' => "string", 'config' => "array"])]
    public static function exportPdf(array $config):array{
        return [
            'label' => 'PDF',
            'filename' => $config['filename'],
            'showHeader' => true,
            'showPageSummary' => false,
            'showFooter' => false,
            'showCaption' => false,
            'alertMsg' => 'The PDF export file will be generated for download.',
            'mime' => 'application/pdf',
            'config' => [
                'mode' => 'c',
                'format' => 'A4',
                'destination' => 'D',
                'marginTop' => 20,
                'marginBottom' => 20,
                'cssInline' => '.kv-wrap{padding:20px;}' .
                    '.kv-align-center{text-align:center;}' .
                    '.kv-align-left{text-align:left;}' .
                    '.kv-align-right{text-align:right;}' .
                    '.kv-align-top{vertical-align:top!important;}' .
                    '.kv-align-bottom{vertical-align:bottom!important;}' .
                    '.kv-align-middle{vertical-align:middle!important;}' .
                    '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                    '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                    '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
                'methods' => [
                    'SetHeader' => [
                        ['odd' => self::setHeader($config['centerContent']), 'even' => self::setHeader($config['centerContent'])]
                    ],
                    'SetFooter' => [
                        ['odd' => self::setFooter(), 'even' => self::setFooter()]
                    ],
                ],
                'options' => [
                    'title' => $config['title'],
                    'subject' => $config['subject'],
                    'keywords' => $config['keywords'],
                ],
                'contentBefore'=>$config['contentBefore'],
                'contentAfter'=>$config['contentAfter']
            ]
        ];
    }
}
