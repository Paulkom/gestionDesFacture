<?php

namespace App\Services;
use Mpdf\Mpdf;
use NumberFormatter;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use App\Database\NativeQueryMySQL;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class LibrairieService
{
    protected $em;
    private $param;
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $param)
    {
        $this->em = $em;
        $this->param = $param;
    }

    public function reformats($data): array
    {
        return array_map(function ($item) {
            $keys = array_keys($item);
            foreach ($keys as $key) {
                if($item[$key] instanceof \DateTime)
                    $item[$key] = $item[$key]->format('d/m/Y');

                if($item[$key] == "En attente")
                    $item[$key] = "<div class='badge badge-secondary fw-bold'>$item[$key]</div>";
                
                if($item[$key]=="Payée")
                    $item[$key] = "<div class='badge badge-success fw-bold'>$item[$key]</div>";

                if($item[$key]=="Partiel")
                    $item[$key] = "<div class='badge badge-primary fw-bold'>$item[$key]</div>";

                if($item[$key]=="Annuler")
                    $item[$key] = "<div class='badge badge-danger fw-bold'>$item[$key]</div>";


                if($item[$key] == "Partielle")
                    $item[$key] = "<div class='badge badge-primary fw-bold'>$item[$key]</div>";
                
                if($item[$key]=="Terminée")
                    $item[$key] = "<div class='badge badge-success fw-bold'>$item[$key]</div>";

                if($item[$key]=="Non livrée")
                    $item[$key] = "<div class='badge badge-danger fw-bold'>$item[$key]</div>";


                if(gettype($item[$key]) === 'boolean') {
                    $value = $item[$key] ? 1 : 0;
                    $checked = $item[$key] ? "checked" : "";
                    $item[$key] = '
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" disabled value="' . $value . '" checked="' . $checked . '"/>
                        </div>';
                }
                
                if(is_numeric($item[$key]) && floor($item[$key]) != false){
                    if(!str_contains($key,'ifu') && !str_contains($key,'tel')){
                        $item[$key] = number_format($item[$key], 2, '.', ' ');
                    }
                }
            }

            return $item;
        }, $data);
    }


    public function getFormattedNumber($value,$locale = 'fr_FR',$style = NumberFormatter::DECIMAL,$precision = 2,$groupingUsed = true,$currencyCode = 'XOF',
    ) {
        $formatter = new NumberFormatter($locale, $style);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $precision);
        $formatter->setAttribute(NumberFormatter::GROUPING_USED, $groupingUsed);
        if ($style == NumberFormatter::CURRENCY) {
            $formatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, $currencyCode);
        }
    
        return $formatter->format($value);
    }


    public function mpdf($htmlPage=[],$titreDoc="Document",$format = "A4-P" ){
    
        if($format == 'A5-L'){

            $mpdf = new Mpdf(['mode' => 'utf-8',
                'format'        => $format,
                'margin_left'   => 5,
                'margin_right'  => 5,
                'margin_top'    => 0,
                'margin_bottom' => 7,
                'margin_footer' => 0,
            ]);

        }else{

            $mpdf = new Mpdf(['mode' => 'utf-8',
                'format'        => $format,
                'margin_left'   => 10,
                'margin_right'  => 10,
                'margin_top'    => 0,
                'margin_bottom' => 10,
                'margin_footer' => 0,
            ]);

        }

        
        
        // $mpdf->SetHTMLHeader('','',true);
        if($format == 'A5-L'){
            $mpdf->SetHTMLFooter('
            <table width="100%" style="padding: 25px;">
                <tr>
                    <td width="33%" align="left" style="padding: 15px;"></td>
                    <td width="33%" align="center" style="padding: 15px;">{DATE j-m-Y H:i:s}</td>
                    <td width="33%" align="right" style="padding: 15px;">{PAGENO}/{nbpg}</td>
                </tr>
            </table>', 'O'
            );
        }else
        {
            $mpdf->SetHTMLFooter('
            <table width="100%" style="padding: 25px;">
                <tr>
                    <td width="50%" align="left" style="padding: 15px;">{DATE j-m-Y H:i:s}</td>
                    <td width="50%" align="right" style="padding: 15px;">{PAGENO}/{nbpg}</td>
                </tr>
            </table>', 'O'
            );
        }
        
        
       // $mpdf->debug = true;
       //dd($htmlPage);
       $cpt = 0;
       //dd($htmlPage);

       //$mpdf->SetHeader('Document Title');

        foreach($htmlPage as $page){
        
            $cpt++;
            
            //dd($page->getContent());
            if($cpt != 1){
                $mpdf->AddPage();
            }
            //dd($page);
            $mpdf->WriteHTML($page);
            //  dd($page);
        }
       //dd($htmlPage);
        $mpdf->Output($titreDoc.'.pdf','I');
    }

    public function codeQr($val){
        // $val = "romastechnologie.com";
        $writer = new PngWriter();
        $qrCode = QrCode::create($val)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(120)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        // $logo = Logo::create('img/logo.png')->setResizeToWidth(60);
        // $label = Label::create('')->setFont(new NotoSans(8));
 
        $qrCodes = [];
        $qrCodes['img'] = $writer->write($qrCode, null)->getDataUri();
        $qrCodes = $writer->write($qrCode,null,null)->getDataUri();
 
        return $qrCodes;
    }


    public function getOptions(NativeQueryMySQL $native, $class, $searchItem, $columnsView)
    {
        $classname = 'App\\Entity\\'.ucfirst($class);
        $default_alias = "o";
        $list_columns_result = "";
        $list_join_columns_result = "";
        $join_sql = "";
        $sql = "SELECT :columns \nFROM $classname $default_alias ";
        $options_cols = isset($parameters['text']) ? explode(';', $parameters['text']) : [];
        $where = "\nWHERE ";
        $search = "";
        $cpt = 0;
        $entities = [];
        if (isset($searchItem) && !empty($searchItem)) {
            $search = $searchItem;

            if (isset($columnsView)) {
                $columns = explode(';', $columnsView);

                for ($i = (count($columns)-1); $i >= 0; $i--) {
                    $field = $columns[$i];
                    //Chercher la position de la sous chaîne ":" dans la colonne $field
                    $position = strpos($field, ":");
                    if($position === false)
                    {
                        if(in_array($field, $options_cols))
                        {
                            $key = array_search ($field, $options_cols);
                            $options_cols[$key] = $default_alias . ".$field";
                        }
                        if(!in_array($field, $options_cols)) $list_columns_result .= $default_alias . ".$field, ";
                        $where .= $default_alias . ".$field LIKE '%$search%' OR ";
                        $this->columnsDefault[$field] = true;
                    }
                    else
                    {
                        $cpt++;
                        //Récupérer la position du # dans la colonne
                        $diese_index = strpos($field, "#");
                        //Récupérer la colonne réelle de la table de jointure
                        $champs = explode('#', substr($field, $diese_index+1, strlen($field)));
                        //Récupérer le nom de la colonne de jointure
                        $join_entity_name = substr($field, 0, $position);
                        $join_table_name = substr($field, $position+1,$diese_index - $position - 1);
                        //Récupérer le nom équivalent dans la base de donnée
                        $database_table_name = $this->camel2dashed($join_table_name);
                        //Récupérer toutes les colonnes de la table jointure $database_table_name
                        $all_cols = $native::GetAllColumnsFromTable($database_table_name);
                        //Récupérer celles qui ne sont ni clé primaire, ni étrangère
                        $h = 0;
                        $concat = ""; $alias = "";
                        foreach ($all_cols as $col) {
                            if($col['Key'] === "")
                            {
                                $assoc_col = lcfirst($this->camelize($col['Field']));
                                if(in_array($assoc_col, $options_cols))
                                {
                                    $key = array_search ($assoc_col, $options_cols);
                                    $options_cols[$key] = $default_alias . '_' . $cpt . ".$assoc_col";
                                }

                                if(property_exists('App\\Entity\\'.ucfirst($join_table_name), $assoc_col) &&
                                    in_array($assoc_col, $champs))
                                {
                                    $h++;
                                    if(!in_array($assoc_col, $options_cols)) $concat .= $default_alias . '_' . $cpt . ".$assoc_col, ' ', ";
                                    $alias .= strtolower($assoc_col) . "_";
                                }
                            }
                        }
                        $alias .= $cpt;

                        $tab = explode(',', rtrim($concat, ", ' ' "));
                        if(sizeof($tab) > 1)
                        {
                            $concat = rtrim($concat, ", ' ' ");
                            $concat = substr_replace(rtrim($concat, ", "), "CONCAT(", 0, 0);
                            $concat = substr_replace($concat, ")", strlen($concat), 0);
                        }
                        else
                            $concat = rtrim($concat, ", ' ' ");

                        $this->columnsDefault[$alias] = true;
                        $list_join_columns_result .= $concat . " AS $alias, ";
                        $where .= "$concat LIKE '%$search%' OR ";
                        //Mettre à jour la chaîne de jointure
                        $join_sql .= "\nLEFT JOIN " . $default_alias . "." . $join_entity_name . " " . $default_alias . '_' . $cpt;
                    }
                }
                $list_columns_result .= $list_join_columns_result;
            }
            $option_concat = "";
            foreach ($options_cols as $cle => $options_col)
            {
                $p = "$options_col, ";
                $list_columns_result = str_replace($p, "", $list_columns_result);
                $list_columns_result = rtrim($list_columns_result, ",$options_col");
                if($cle === 0 && count($options_cols)-1 !== $cle) $option_concat .= "CONCAT(";
                if(count($options_cols) > 1)
                    $option_concat .= $options_col . (count($options_cols)-1 === $cle ? ") AS text" : ", ' ', ");
                else
                    $option_concat .= "$options_col AS text";
            }
            $list_columns_result .= $option_concat;
            $list_columns_result = rtrim($list_columns_result, ", ");
            if(strlen($where) > 6)
                $where = rtrim($where, " OR ");
            else
                $where = "";
            //Reconstruire la requête avec les jointures
            $sql .= $join_sql;
            $sql = str_replace(":columns", $list_columns_result, $sql) . $where;

            $entities = $this->em->createQuery($sql)
                ->getArrayResult();
        }

        return $entities;
    }

    public function reformat($data): array
    {
        return array_map(function ($item) {
            return $item;
        }, $data);
    }

    public function arraySearch($array, $keyword)
    {
        return array_filter($array, function ($a) use ($keyword) {
            return (boolean) preg_grep("/$keyword/i", (array) $a);
        });
    }

    public function filterArray($array, $allowed = [])
    {
        $arr = array_filter(
            $array,
            function ($val, $key) use ($allowed) { // N.b. $val, $key not $key, $val
                return isset($allowed[$key]) && ($allowed[$key] === true || $allowed[$key] === $val);
            },
            ARRAY_FILTER_USE_BOTH
        );

        return $arr;
    }

    public function camelize($input, $separator = '_')
    {
        return str_replace($separator, '', ucwords($input, $separator));
    }

    public function camel2dashed($className) 
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $className));
    }
}