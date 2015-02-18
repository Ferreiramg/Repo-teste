<?php

namespace Client;

use Model\Cached\Memory;

/**
 * Description of ExportarData
 *
 * @author Luis Paulo
 */
class ExportarData extends AbstracClient {

    public function execute() {

        $key = EntradaRead::C_KEY . $this->params[0] . \Model\Silo::getSessionYear();
        $data = Memory::getInstance()->meminstance->get($key);
        $produtor = new \Model\Produtor($this->params[0] - 1);
        if ($data === false) {
            throw new \Exceptions\ClientExceptionResponse("Não existe data no cache!");
        }
        $excel = new \PHPExcel();
        $excel->setActiveSheetIndex(0);

        $this->armazenagem($produtor, $excel, $data);

        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $this->download($objWriter);
    }

    public function hasRequest() {
        return \Main::$Action === 'export';
    }

    /**
     * @codeCoverageIgnore
     * @param \Model\Produtor $produtor
     * @param \PHPExcel $excel
     * @param string $data
     * @return \PHPExcel
     */
    private function armazenagem(\Model\Produtor $produtor, \PHPExcel $excel, $data) {
        $sheet = $excel->getActiveSheet();
        $sheet->setCellValue('A1', $produtor->nome)
                ->setCellValue('D1', $produtor->getTaxa())
                ->setCellValue('A2', 'Dias')
                ->setCellValue('B2', 'Entrada')
                ->setCellValue('C2', 'Saidas')
                ->setCellValue('D2', 'Armazenagem')
                ->setCellValue('E2', 'Saldo')
                ->setCellValue('F2', 'Obs.');
        $sheet->getStyle('A1:F2')->applyFromArray(
                array(
                    'fill' => [
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'd3d3d3')
                    ],
                    'font' => [
                        'bold' => true,
                    ]
                )
        );
        $sheet->getStyle('E2')->applyFromArray(
                array('alignment' => [
                        'wrap' => true,
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ]
        ));
        $sheet->getStyle('E2:E400')->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('B2:B400')->getNumberFormat()->setFormatCode('#,##0.00');
        $row = 2;
        foreach (json_decode($data, true) as $dataRow) {
            ++$row;
            $sheet->setCellValue('A' . $row, date('d/m/Y', strtotime($dataRow['dia'])))
                    ->setCellValue('B' . $row, round($dataRow['entrada'], 2))
                    ->setCellValue('C' . $row, $dataRow['saida'])
                    ->setCellValue('D' . $row, $dataRow['desconto'])
                    ->setCellValue('E' . $row, round($dataRow['saldo'], 2))
                    ->setCellValue('F' . $row, $dataRow['observacao']);
        }
        return $excel;
    }

    /**
     * @codeCoverageIgnore
     */
    private function download($objWriter) {
        if (\Configs::getInstance()->get('debug') === true) {
            return null;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="amz-export.xls"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }

}
