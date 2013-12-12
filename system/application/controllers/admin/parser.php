<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Denis
 * Date: 09.05.11
 * Time: 19:40
 * To change this template use File | Settings | File Templates.
 */
 
class Parser extends Controller {
    function __construct() {
        parent::Controller();
    }

    public function index() {
        require_once( APPPATH.'/libraries/PHPExcel/PHPExcel.php');
        require_once( APPPATH.'/libraries/CSV.php');

        $full_path = 'Price_SPV_2010_part1.xls';

        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load( $full_path );
        $activeSheet = $this->parseFile( $objPHPExcel );
    }

    private function parseFile(PHPExcel $objPHPExcel) {
        $sheet_str = '';
        $row_start = 0;
        $issetCoordinates = false;
        $coordinateRow = 0;

        $reportConfig = array(
            'Сервіс'    =>  array(
                'title'     =>  array( 'name' => 'Назва', 'coordinate' => array( 'col' => array( 1 ) ) ),
                'desc'      =>  array( 'name' => 'Опис', 'coordinate' => array( 'col' => array( 2, 3 ) ) ),
                'cost'      =>  array( 'name' => 'Ціна грн.з ПДВ', 'coordinate' => array( 'col' => array( 4 ) ) )
            ),
            'Замки'    =>  array(
                'title'     =>  array( 'name' => 'Назва', 'coordinate' => array( 'col' => array( 1 ) ) ),
                'desc'      =>  array( 'name' => 'Опис', 'coordinate' => array( 'col' => array( 2, 3 ) ) ),
                'cost'      =>  array( 'name' => 'Ціна грн.з ПДВ', 'coordinate' => array( 'col' => array( 4 ) ) )
            ),
            'Фурнітура'    =>  array(
                'title'     =>  array( 'name' => 'Назва', 'coordinate' => array( 'col' => array( 1 ) ) ),
                'desc'      =>  array( 'name' => 'Опис', 'coordinate' => array( 'col' => array( 2, 3 ) ) ),
                'cost'      =>  array( 'name' => 'Ціна грн.з ПДВ', 'coordinate' => array( 'col' => array( 4 ) ) )
            ),
        );
        $reportSheetsData = array();

        $sheetNames = $objPHPExcel->getSheetNames();

        foreach($sheetNames as $index => $name)
        {
            if( array_key_exists( $name, $reportConfig ) )
            {
                $objWorksheet = $objPHPExcel->getSheetByName($name);
                $highestRow = $objWorksheet->getHighestRow();
                $highestColumn = $objWorksheet->getHighestColumn();

                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn)+1;

                /*for ($row = 0; $row <= $highestRow; ++$row) {
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $value = trim( $objWorksheet->getCellByColumnAndRow($col, $row)->getValue() );
                        if( $value == $reportConfig[$name]['title']['name']
                            || $value == $reportConfig[$name]['desc']['name']
                            || $value == $reportConfig[$name]['cost']['name']
                        )
                        {
                            $row_start = ++$row;
                            break;
                        }
                    }
                    if( !empty( $row_start ) ) break;
                }*/

                for ($row = 0; $row <= $highestRow; ++$row)
                {
                    $toPassRow = false;

                    $value = "";
                    foreach( $reportConfig[$name]['title']['coordinate']['col'] as $col )
                    {
                        $_value = trim( $objWorksheet->getCellByColumnAndRow( $col, $row )->getValue() );
                        $value .= $_value;
                    }
                    $reportSheetsData[$name][$row]['title'] = $value;

                    $value = "";
                    foreach( $reportConfig[$name]['desc']['coordinate']['col'] as $index => $col )
                    {
                        $_value = trim( $objWorksheet->getCellByColumnAndRow( $col, $row )->getValue() );
                        if( empty( $_value ) && $index == 0 )
                        {
                            $_value = trim( $objWorksheet->getCellByColumnAndRow( $col, ($row -1) )->getValue() );
                        }
                        $value .= $_value . " ";
                    }
                    $reportSheetsData[$name][$row]['desc'] = $value;

                    $value = "";
                    foreach( $reportConfig[$name]['cost']['coordinate']['col'] as $col )
                    {
                        $_value = floatval( trim( $objWorksheet->getCellByColumnAndRow( $col, $row )->getValue() ) );
                        if( empty( $_value ) ) {
                            $toPassRow = true;
                            break;
                        }
                        $value .= $_value;
                    }
                    $reportSheetsData[$name][$row]['cost'] = $value;
                    if( $toPassRow ) {
                        unset( $reportSheetsData[$name][$row] );
                    }
                    else
                    {
                        if( empty( $reportSheetsData[$name][$row]['title'] )
                        && !empty( $reportSheetsData[$name][$row]['cost'] ) ) {
                            $reportSheetsData[$name][$row]['title'] = $reportSheetsData[$name][$row - 1]['title'];
                        }
                    }
                }
            }
        }
echo "<pre>";
    var_dump($reportSheetsData);
echo "</pre>";exit;
        if( !empty( $reportSheetsData ) ) {
            $this->load->model('items_mdl','items');
            foreach( $reportSheetsData as $sheetName => $rows ) {
                foreach( $rows as $row => $data ) {
                    $item_data = array(
                        'item_title' => $data['title'],
                        'item_content' => $data['desc'],
                        'item_added' => date("Y-m-d H:i:s"),
                        'item_update' => date("Y-m-d H:i:s"),
                        'item_production' => date("Y-m-d H:i:s"),
                        'item_type' => 'products',
                        'item_mode' => 'close',
                        'item_price' => $data['cost']
                    );
//                    $item_id = $this->items->save_item($item_data);
                }
            }
        }
    }
}
