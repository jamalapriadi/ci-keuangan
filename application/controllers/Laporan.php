<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing as drawing; // Instead PHPExcel_Worksheet_Drawing
use PhpOffice\PhpSpreadsheet\Style\Alignment as alignment; // Instead PHPExcel_Style_Alignment
use PhpOffice\PhpSpreadsheet\Style\Fill as fill; // Instead PHPExcel_Style_Fill
use PhpOffice\PhpSpreadsheet\Style\Color as color_; //Instead PHPExcel_Style_Color
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup as pagesetup;
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter;
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing;

class Laporan extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        $this->load->library('pdf');
        $this->load->model('M_penjualan');
    }

    function po_intern(){
        $cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
            $d['judul']="PO Intern";
			
			$d['prg']= $this->config->item('prg');
			$d['web_prg']= $this->config->item('web_prg');
			
			$d['nama_program']= $this->config->item('nama_program');
			$d['instansi']= $this->config->item('instansi');
			$d['usaha']= $this->config->item('usaha');
			$d['alamat_instansi1']= $this->config->item('alamat_instansi1');
            $d['alamat_instansi2']= $this->config->item('alamat_instansi2');
            
            $d['po']=$this->M_penjualan->get_all_po_intern();
            $d['user']=$this->M_penjualan->get_all_user();


            $d['content'] = $this->load->view('laporan/po_intern/view', $d, true);		
			$this->load->view('home',$d);
        }
    }

    function cetak_po_intern(){
        $this->form_validation->set_rules('no_po', 'No Po', 'required');
        $this->form_validation->set_rules('dibuat', 'Di Buat', 'required');
        $this->form_validation->set_rules('diperiksa', 'Di Periksa', 'required');
        $this->form_validation->set_rules('disetujui', 'Di Setujui', 'required');

        if ($this->form_validation->run() == FALSE){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors'
            );
        }else{
            $type=$this->input->post('export');

            if($type=="pdf"){
                $data['po']=$this->M_penjualan->po_intern_by_id($this->input->post('no_po'))->row_array();
                $data['dibuat']=$this->input->post('dibuat');
                $data['diperiksa']=$this->input->post('diperiksa');
                $data['disetujui']=$this->input->post('disetujui');
                $data['marketing']=$this->session->userdata('nama_lengkap');

                $this->load->library('pdf');

                $this->pdf->setPaper('A4', 'potrait');
                $this->pdf->filename = "po_intern.pdf";
                $this->pdf->load_view('laporan/po_intern/po_intern_pdf',$data,true);    
            }else if($type=="excel"){
                $po=$this->M_penjualan->po_intern_by_id($this->input->post('no_po'))->row_array();
                $dibuat=$this->input->post('dibuat');
                $diperiksa=$this->input->post('diperiksa');
                $disetujui=$this->input->post('disetujui');
                $marketing=$this->session->userdata('nama_lengkap');

                $spreadsheet = new Spreadsheet();

                // $helper->log('Add a drawing to the header');
                // $drawing = new HeaderFooterDrawing();
                // $drawing->setName('PhpSpreadsheet logo');
                // $drawing->setPath(__DIR__ . '/../../asset/images/logo/logo-neonlite.png');
                // $drawing->setCoordinates('B15');
                // $drawing->setOffsetX(110);
                // $drawing->setRotation(25);
                // $drawing->setHeight(36);
                // $spreadsheet->getActiveSheet()
                //     ->getHeaderFooter()
                //     ->addImage($drawing, HeaderFooter::IMAGE_HEADER_LEFT);


                // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                // $drawing->setName('Paid');
                // $drawing->setDescription('Paid');
                // $drawing->setPath($logo);
                // $drawing->setCoordinates('B15');
                // $drawing->setOffsetX(110);
                // $drawing->setRotation(25);
                // $drawing->getShadow()->setVisible(true);
                // $drawing->getShadow()->setDirection(45);

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('B2','No.')
                    ->setCellValue('C1','P.O.INTERN')
                    ->setCellValue('C2',$po['no_po'])
                    ->setCellValue('G1','ORDER : ')
                    ->setCellValue('H1',$marketing)
                    ->setCellValue('G2','COPY : 1');


                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'REFERENSI')
                    ->setCellValue('A5', 'Nama Pemesan')
                    ->setCellValue('A6','Surat Pesanan / SPK. No.')
                    ->setCellValue('A7','Surat Penawaran No')
                    ->setCellValue('A8','Di terima di PT. Neonlite tgl')
                    ->setCellValue('B4',':')
                    ->setCellValue('B5',':')
                    ->setCellValue('B6',':')
                    ->setCellValue('B7',':')
                    ->setCellValue('B8',':')
                    ->setCellValue('C4','')
                    ->setCellValue('C5',$po['nama_client'])
                    ->setCellValue('C6',$po['no_spk'])
                    ->setCellValue('C7',$po['no_penawaran'])
                    ->setCellValue('C8',$po['tgl_terima'])
                    ->setCellValue('F6','Tgl : '.$po['tgl_po'])
                    ->setCellValue('F7','Tgl : '.$po['tgl_penawaran'] );

                $spreadsheet->getActiveSheet()->getStyle('A4')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

                $spreadsheet->getActiveSheet()->getStyle('A5:A8')
                    ->getFont()
                    ->getColor()
                    ->setARGB('ad0000');

                $spreadsheet->getActiveSheet()->getStyle('A12:A41')
                    ->getFont()
                    ->getColor()
                    ->setARGB('ad0000');

                $spreadsheet->getActiveSheet()->getStyle('A10')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

                $spreadsheet->getActiveSheet()->getStyle('G1')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

                $spreadsheet->getActiveSheet()->getStyle('H1')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

                $spreadsheet->getActiveSheet()->getStyle('G2')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

                $spreadsheet->getActiveSheet()->getStyle('H2')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

                $spreadsheet->getActiveSheet()->getStyle('B2')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

                $spreadsheet->getActiveSheet()->getStyle('H1')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                    

                $spreadsheet->getActiveSheet()->getStyle('C1')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

                $spreadsheet->getActiveSheet()->getStyle('C2')
                    ->getFont()
                    ->setBold(true);

                $spreadsheet->getActiveSheet()->getStyle('C5')
                    ->getFont()
                    ->setBold(true);

                $spreadsheet->getActiveSheet()->getStyle('A46')
                    ->getFont()
                    ->setBold(true);

                $spreadsheet->getActiveSheet()->mergeCells('A41:E41');
                $spreadsheet->getActiveSheet()->mergeCells('A46:E46');
                $spreadsheet->getActiveSheet()->mergeCells('F46:G46');

                $spreadsheet->getActiveSheet()->getStyle('F41')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB('ad0000');

                $spreadsheet->getActiveSheet()->getStyle('A46')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB('ad0000');

                $spreadsheet->getActiveSheet()->getStyle('F46')
                    ->getFont()
                    ->setBold(true)
                    ->getColor()
                    ->setARGB('ad0000');

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A10', 'SPESIFIKASI')
                    ->setCellValue('A11', '')
                    ->setCellValue('A12', 'Pesanan / Pekerjaan')
                    ->setCellValue('A14', 'Ukuran')
                    ->setCellValue('A15', 'Jumlah')
                    ->setCellValue('A17', 'Warna')
                    ->setCellValue('A18', 'Bahan')
                    ->setCellValue('A19', 'Proses')
                    ->setCellValue('A20', 'Ketentuan lain')
                    ->setCellValue('B10', ':')
                    ->setCellValue('B12', ':')
                    ->setCellValue('B14', ':')
                    ->setCellValue('B15', ':')
                    ->setCellValue('B17', ':')
                    ->setCellValue('B18', ':')
                    ->setCellValue('B19', ':')
                    ->setCellValue('B20', ':')
                    ->setCellValue('C12', $po['pekerjaan'])
                    ->setCellValue('C14', $po['ukuran'])
                    ->setCellValue('C15', $po['jumlah'])
                    ->setCellValue('C17', $po['warna'])
                    ->setCellValue('C18', $po['bahan'])
                    ->setCellValue('C19', $po['proses'])
                    ->setCellValue('C20', $po['ketentuan']);

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A29', 'Cara Pembayaran')
                    ->setCellValue('A30', 'Proof')
                    ->setCellValue('B29', ':')
                    ->setCellValue('B30', ':');

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A34', 'Waktu Penyerahan / pemasangan')
                    ->setCellValue('A35', 'Alamat Penyerahan / pemasangan')
                    ->setCellValue('B34', ':')
                    ->setCellValue('B35', ':')
                    ->setCellValue('C34', $po['tgl_pemasangan'])
                    ->setCellValue('C35', $po['alamat_pasang']);

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A37', 'Harga Satuan')
                    ->setCellValue('A38', 'Harga Total')
                    ->setCellValue('B37', ':')
                    ->setCellValue('B38', ':')
                    ->setCellValue('C37', $po['harga'])
                    ->setCellValue('C38', $po['total']);

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A41', 'Dibuat                              Diperiksa                                               Disetujui')
                    ->setCellValue('F41','Jakarta, ')
                    ->setCellValue('G41',date('d-M-Y'));

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A46', $dibuat.'                                '.$diperiksa.'                                                '.$disetujui)
                    ->setCellValue('F46','( '.$marketing.' )');

                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
                $drawing->setName('PhpSpreadsheet logo');
                $drawing->setPath('./asset/images/logo/logo-neonlite.png');
                $drawing->setHeight(36);

                $spreadsheet->getActiveSheet()->getHeaderFooter()->addImage($drawing, \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter::IMAGE_HEADER_LEFT);
                $spreadsheet->getActiveSheet()->setShowGridlines(false);

                // Rename worksheet
                $spreadsheet->getActiveSheet()
                    ->setTitle('Report Excel '.date('d-m-Y H'));

                //set dimension column
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);

                // Redirect output to a client’s web browser (Xlsx)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Report Excel.xlsx"');
                header('Cache-Control: max-age=0');

                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                exit;
            }
        }
    }

    function po_intern_pdf(){
        $this->form_validation->set_rules('no_po', 'No Po', 'required');
        $this->form_validation->set_rules('dibuat', 'Di Buat', 'required');
        $this->form_validation->set_rules('diperiksa', 'Di Periksa', 'required');
        $this->form_validation->set_rules('disetujui', 'Di Setujui', 'required');

        if ($this->form_validation->run() == FALSE){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors'
            );
        }else{
            $data['po']=$this->M_penjualan->po_intern_by_id($this->input->post('no_po'))->row_array();
            $data['dibuat']=$this->input->post('dibuat');
            $data['diperiksa']=$this->input->post('diperiksa');
            $data['disetujui']=$this->input->post('disetujui');
            $data['marketing']=$this->session->userdata('nama_lengkap');

            $this->load->library('pdf');

            $this->pdf->setPaper('A4', 'potrait');
            $this->pdf->filename = "po_intern.pdf";
            $this->pdf->load_view('laporan/po_intern/po_intern_pdf',$data,true);    
        }

        

        // $this->

    }

    function po_intern_excel(){
        $this->form_validation->set_rules('no_po', 'No Po', 'required');
        $this->form_validation->set_rules('dibuat', 'Di Buat', 'required');
        $this->form_validation->set_rules('diperiksa', 'Di Periksa', 'required');
        $this->form_validation->set_rules('disetujui', 'Di Setujui', 'required');

        if ($this->form_validation->run() == FALSE){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors'
            );
        }else{
            $po=$this->M_penjualan->po_intern_by_id($this->input->post('no_po'))->row_array();
            $dibuat=$this->input->post('dibuat');
            $diperiksa=$this->input->post('diperiksa');
            $disetujui=$this->input->post('disetujui');
            $marketing=$this->session->userdata('nama_lengkap');

            $spreadsheet = new Spreadsheet();

            // $helper->log('Add a drawing to the header');
            // $drawing = new HeaderFooterDrawing();
            // $drawing->setName('PhpSpreadsheet logo');
            // $drawing->setPath(__DIR__ . '/../../asset/images/logo/logo-neonlite.png');
            // $drawing->setCoordinates('B15');
            // $drawing->setOffsetX(110);
            // $drawing->setRotation(25);
            // $drawing->setHeight(36);
            // $spreadsheet->getActiveSheet()
            //     ->getHeaderFooter()
            //     ->addImage($drawing, HeaderFooter::IMAGE_HEADER_LEFT);


            // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            // $drawing->setName('Paid');
            // $drawing->setDescription('Paid');
            // $drawing->setPath($logo);
            // $drawing->setCoordinates('B15');
            // $drawing->setOffsetX(110);
            // $drawing->setRotation(25);
            // $drawing->getShadow()->setVisible(true);
            // $drawing->getShadow()->setDirection(45);

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('B2','No.')
                ->setCellValue('C1','P.O.INTERN')
                ->setCellValue('C2',$po['no_po'])
                ->setCellValue('G1','ORDER : ')
                ->setCellValue('H1',$marketing)
                ->setCellValue('G2','COPY : 1');


            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A4', 'REFERENSI')
                ->setCellValue('A5', 'Nama Pemesan')
                ->setCellValue('A6','Surat Pesanan / SPK. No.')
                ->setCellValue('A7','Surat Penawaran No')
                ->setCellValue('A8','Di terima di PT. Neonlite tgl')
                ->setCellValue('B4',':')
                ->setCellValue('B5',':')
                ->setCellValue('B6',':')
                ->setCellValue('B7',':')
                ->setCellValue('B8',':')
                ->setCellValue('C4','')
                ->setCellValue('C5',$po['nama_client'])
                ->setCellValue('C6',$po['no_spk'])
                ->setCellValue('C7',$po['no_penawaran'])
                ->setCellValue('C8',$po['tgl_terima'])
                ->setCellValue('F6','Tgl : '.$po['tgl_po'])
                ->setCellValue('F7','Tgl : '.$po['tgl_penawaran'] );

            $spreadsheet->getActiveSheet()->getStyle('A4')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

            $spreadsheet->getActiveSheet()->getStyle('A5:A8')
                ->getFont()
                ->getColor()
                ->setARGB('ad0000');

            $spreadsheet->getActiveSheet()->getStyle('A12:A41')
                ->getFont()
                ->getColor()
                ->setARGB('ad0000');

            $spreadsheet->getActiveSheet()->getStyle('A10')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

            $spreadsheet->getActiveSheet()->getStyle('G1')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

            $spreadsheet->getActiveSheet()->getStyle('H1')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

            $spreadsheet->getActiveSheet()->getStyle('G2')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

            $spreadsheet->getActiveSheet()->getStyle('H2')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

            $spreadsheet->getActiveSheet()->getStyle('B2')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

            $spreadsheet->getActiveSheet()->getStyle('H1')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                

            $spreadsheet->getActiveSheet()->getStyle('C1')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

            $spreadsheet->getActiveSheet()->getStyle('C2')
                ->getFont()
                ->setBold(true);

            $spreadsheet->getActiveSheet()->getStyle('C5')
                ->getFont()
                ->setBold(true);

            $spreadsheet->getActiveSheet()->getStyle('A46')
                ->getFont()
                ->setBold(true);

            $spreadsheet->getActiveSheet()->mergeCells('A41:E41');
            $spreadsheet->getActiveSheet()->mergeCells('A46:E46');
            $spreadsheet->getActiveSheet()->mergeCells('F46:G46');

            $spreadsheet->getActiveSheet()->getStyle('F41')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB('ad0000');

            $spreadsheet->getActiveSheet()->getStyle('A46')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB('ad0000');

            $spreadsheet->getActiveSheet()->getStyle('F46')
                ->getFont()
                ->setBold(true)
                ->getColor()
                ->setARGB('ad0000');

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A10', 'SPESIFIKASI')
                ->setCellValue('A11', '')
                ->setCellValue('A12', 'Pesanan / Pekerjaan')
                ->setCellValue('A14', 'Ukuran')
                ->setCellValue('A15', 'Jumlah')
                ->setCellValue('A17', 'Warna')
                ->setCellValue('A18', 'Bahan')
                ->setCellValue('A19', 'Proses')
                ->setCellValue('A20', 'Ketentuan lain')
                ->setCellValue('B10', ':')
                ->setCellValue('B12', ':')
                ->setCellValue('B14', ':')
                ->setCellValue('B15', ':')
                ->setCellValue('B17', ':')
                ->setCellValue('B18', ':')
                ->setCellValue('B19', ':')
                ->setCellValue('B20', ':')
                ->setCellValue('C12', $po['pekerjaan'])
                ->setCellValue('C14', $po['ukuran'])
                ->setCellValue('C15', $po['jumlah'])
                ->setCellValue('C17', $po['warna'])
                ->setCellValue('C18', $po['bahan'])
                ->setCellValue('C19', $po['proses'])
                ->setCellValue('C20', $po['ketentuan']);

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A29', 'Cara Pembayaran')
                ->setCellValue('A30', 'Proof')
                ->setCellValue('B29', ':')
                ->setCellValue('B30', ':');

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A34', 'Waktu Penyerahan / pemasangan')
                ->setCellValue('A35', 'Alamat Penyerahan / pemasangan')
                ->setCellValue('B34', ':')
                ->setCellValue('B35', ':')
                ->setCellValue('C34', $po['tgl_pemasangan'])
                ->setCellValue('C35', $po['alamat_pasang']);

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A37', 'Harga Satuan')
                ->setCellValue('A38', 'Harga Total')
                ->setCellValue('B37', ':')
                ->setCellValue('B38', ':')
                ->setCellValue('C37', $po['harga'])
                ->setCellValue('C38', $po['total']);

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A41', 'Dibuat                              Diperiksa                                               Disetujui')
                ->setCellValue('F41','Jakarta, ')
                ->setCellValue('G41',date('d-M-Y'));

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A46', $dibuat.'                                '.$diperiksa.'                                                '.$disetujui)
                ->setCellValue('F46','( '.$marketing.' )');

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
            $drawing->setName('PhpSpreadsheet logo');
            $drawing->setPath('./asset/images/logo/logo-neonlite.png');
            $drawing->setHeight(36);

            $spreadsheet->getActiveSheet()->getHeaderFooter()->addImage($drawing, \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter::IMAGE_HEADER_LEFT);
            $spreadsheet->getActiveSheet()->setShowGridlines(false);

            // Rename worksheet
            $spreadsheet->getActiveSheet()
                ->setTitle('Report Excel '.date('d-m-Y H'));

            //set dimension column
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(35);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);

            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Report Excel.xlsx"');
            header('Cache-Control: max-age=0');

            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        }
    }

    function po_intern_word(){
        // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        /* Note: any element you append to a document must reside inside of a Section. */

        // Adding an empty Section to the document...
        $section = $phpWord->addSection();
        // Adding Text element to the Section having font styled by default...
        
        // Add text elements
        $section->addText('Hello World!');
        $section->addTextBreak(2);
        $section->addText('Mohammad Rifqi Sucahyo.', array('name'=>'Verdana', 'color'=>'006699'));
        $section->addTextBreak(2);
        $phpWord->addFontStyle('rStyle', array('bold'=>true, 'italic'=>true, 'size'=>16));
        $phpWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
        // Save File / Download (Download dialog, prompt user to save or simply open it)
        $section->addText('Ini Adalah Demo PHPWord untuk CI', 'rStyle', 'pStyle');

        $styleTable = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80);
        $styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF');
        $styleCell = array('valign' => 'center');
        $styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
        $fontStyle = array('bold' => true, 'align' => 'center');
        $phpWord->addTableStyle('Fancy Table', $styleTable, $styleFirstRow);
        $table = $section->addTable('Fancy Table');
        $table->addRow(900);
        $table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 1'), $fontStyle);
        $table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 2'), $fontStyle);
        $table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 3'), $fontStyle);
        $table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 4'), $fontStyle);
        $table->addCell(500, $styleCellBTLR)->addText(htmlspecialchars('Row 5'), $fontStyle);

        for ($i = 1; $i <= 8; $i++) {
            $table->addRow();
            $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}"));
            $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}"));
            $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}"));
            $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}"));
            $text = (0== $i % 2) ? 'X' : '';
            $table->addCell(500)->addText(htmlspecialchars($text));
        }
        
        $filename='just_some_random_name.docx'; //save our document as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;


    }
}