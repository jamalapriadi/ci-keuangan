<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan extends CI_Controller{
    /**
     * author : jamal apriadi
     * @web
     * @keterangan : controller untuk penjualan
     * @menu : administrasi -> penjualan
     */

    function __construct(){
        parent::__construct();
        $this->load->model('M_penjualan');
    }

    function index($rowno=0){
        $cek = $this->session->userdata('logged_in');
        if(!empty($cek)){
            $d['prg']= $this->config->item('prg');
            $d['web_prg']= $this->config->item('web_prg');

            $d['nama_program']= $this->config->item('nama_program');
            $d['instansi']= $this->config->item('instansi');
            $d['usaha']= $this->config->item('usaha');
            $d['alamat_instansi1']= $this->config->item('alamat_instansi1');
            $d['alamat_instansi2']= $this->config->item('alamat_instansi2');

            $perpage=$this->config->item('limit_data');

            if($rowno!=0){
                $rowno=($rowno-1)*$perpage;
            }

            $cari = $this->input->post('txt_cari');

            //semua data di order penjualan
            $allcount=$this->M_penjualan->jumlahOrderPenjualan();

            //dapatkab data penjualan
            $order = $this->M_penjualan->tampilOrderPenjualan($rowno,$perpage,$cari);

            // Pagination
            $config['base_url'] = site_url() . '/penjualan/index/';
            $config['total_rows'] = $allcount;
            $config['per_page'] = $perpage;
            $config['uri_segment'] = 3;
            $config['next_link'] = 'Lanjut &raquo;';
            $config['prev_link'] = '&laquo; Kembali';
            $config['last_link'] = '<b>Terakhir &raquo; </b>';
            $config['first_link'] = '<b> &laquo; Pertama</b>';

            // Initialize
            $this->pagination->initialize($config);

            // Initialize $data Array
            $d['pagination'] = $this->pagination->create_links();
            $d['data'] = $order;
            $d['row'] = $rowno;


            $d['judul']="Penjualan";

            /*another data */
            $d['list_rek']=$this->M_penjualan->list_rek();
            $d['list_po'] = $this->M_penjualan->list_po();

            $d['content'] = $this->load->view('vpenjualan/detail_penjualan', $d, true);
            $this->load->view('home',$d);
        }else{
            header('location:'.base_url());
        }
    }

    /**
     * mencari nama po berdasarkan id
     * @param = no_po
     * url : penjualan/no_po_by_id
     */
    function no_po_by_id(){
        $no_po=$this->input->get('no_po');

        $where = array('no_po' => $no_po);
        $list_no_po=$this->M_penjualan->no_po_by_id($no_po)->row();

        echo json_encode($list_no_po);
    }

    /**
     *
     */
    function simpan(){
        $this->form_validation->set_rules('no_po', 'No Po', 'required');
		$this->form_validation->set_rules('id_client', 'ID Client', 'required');
		$this->form_validation->set_rules('detail_pesanan', 'Detail Pesanan', 'required');
        $this->form_validation->set_rules('ukuran', 'Ukuran', 'required');
        $this->form_validation->set_rules('no_rek', 'No Rek', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required');
        $this->form_validation->set_rules('total', 'Total', 'required');
        $this->form_validation->set_rules('tgl_pemasangan', 'Tgl Pemasangan', 'required');

		if ($this->form_validation->run() == FALSE){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>json_encode(
                    array(
                        'no_po' => form_error('no_po'),
                        'id_client' => form_error('id_client'),
                        'detail_pesanan' => form_error('detail_pesanan')
                    )
                )
            );
        }else{
            $form=array(
                'no_po'=>$this->input->post('no_po'),
                'tgl_po'=>date('Y-m-d',strtotime($this->input->post('tgl_po'))),
                'tgl_pemasangan'=>date('Y-m-d',strtotime($this->input->post('tgl_pemasangan'))),
                'id_client'=>$this->input->post('id_client'),
                'detail_pesanan'=>$this->input->post('detail_pesanan'),
                'ukuran'=>$this->input->post('ukuran'),
                'jumlah'=>$this->input->post('jumlah'),
                'no_rek'=>$this->input->post('no_rek'),
                'harga'=>str_replace(',','',$this->input->post('harga')),
                'ppn'=>str_replace(',','',$this->input->post('ppn')),
                'total'=>str_replace(',','',$this->input->post('total')),
                'input_by'=>$this->session->userdata('username'),
                'last_update'=>date('Y-m-d h:m:s')
            );

            $simpan_detail_penjualan=$this->M_penjualan->simpan_detail_penjualan($form);

            if($simpan_detail_penjualan){
                //jika data berhasil disimpan, maka update keterangan di tabel po intern menjadi 2
                $no_po=$this->input->post('no_po');
                $updateData=array(
                    'ket'=>2
                );

                $this->M_penjualan->update_po_intern($no_po,$updateData);

                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil disimpan'
                );
            }else{
                $data=array(
                    'success'=>false,
                    'pesan'=>'Data gagal disimpan'
                );
            }
        }

        echo json_encode($data);
    }

    /**
     * update order by id
     */
    function update_order(){
        $this->form_validation->set_rules('order_id', 'Order Id', 'required');
        $this->form_validation->set_rules('no_po', 'No Po', 'required');
		$this->form_validation->set_rules('id_client', 'ID Client', 'required');
		$this->form_validation->set_rules('detail_pesanan', 'Detail Pesanan', 'required');
        $this->form_validation->set_rules('ukuran', 'Ukuran', 'required');
        $this->form_validation->set_rules('no_rek', 'No Rek', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required');
        $this->form_validation->set_rules('total', 'Total', 'required');
        $this->form_validation->set_rules('tgl_pemasangan', 'Tgl Pemasangan', 'required');

		if ($this->form_validation->run() == FALSE){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>json_encode(
                    array(
                        'no_po' => form_error('no_po'),
                        'id_client' => form_error('id_client'),
                        'detail_pesanan' => form_error('detail_pesanan')
                    )
                )
            );
        }else{
            $order_id=$this->input->post('order_id');

            $form=array(
                'no_po'=>$this->input->post('no_po'),
                'tgl_po'=>date('Y-m-d',strtotime($this->input->post('tgl_po'))),
                'tgl_pemasangan'=>date('Y-m-d',strtotime($this->input->post('tgl_pemasangan'))),
                'id_client'=>$this->input->post('id_client'),
                'detail_pesanan'=>$this->input->post('detail_pesanan'),
                'ukuran'=>$this->input->post('ukuran'),
                'jumlah'=>$this->input->post('jumlah'),
                'no_rek'=>$this->input->post('no_rek'),
                'harga'=>str_replace(',','',$this->input->post('harga')),
                'ppn'=>str_replace(',','',$this->input->post('ppn')),
                'total'=>str_replace(',','',$this->input->post('total')),
                'input_by'=>$this->session->userdata('username'),
                'last_update'=>date('Y-m-d h:m:s')
            );

            $simpan_detail_penjualan=$this->M_penjualan->update_order($order_id,$form);

            if($simpan_detail_penjualan){
                //jika data berhasil disimpan, maka update keterangan di tabel po intern menjadi 2
                $no_po=$this->input->post('no_po');
                $updateData=array(
                    'ket'=>2
                );

                $this->M_penjualan->update_po_intern($no_po,$updateData);

                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil disimpan'
                );
            }else{
                $data=array(
                    'success'=>false,
                    'pesan'=>'Data gagal disimpan'
                );
            }
        }

        echo json_encode($data);
    }

    /**
     * hapus po by no_po
     * @param=no_po
     */
    function hapus_po(){
        $this->form_validation->set_rules('no_po', 'No Po', 'required');

        if ($this->form_validation->run() == FALSE){
            $data=array(
                'success'=>false,
                'no_po'=>$this->input->post('no_po')
            );
        }else{
            //update ket di po_intern jadi 1
            $no_po=$this->input->post('no_po');
            $updateData=array(
                'ket'=>1
            );

            $this->M_penjualan->update_po_intern($no_po,$updateData);

            //kemudian hapus order_penjualan dengan no_po ini
            $this->M_penjualan->hapus_order_by_po($no_po);

            $data=array(
                'success'=>true,
                'no_po'=>$this->input->post('no_po')
            );
        }

        echo json_encode($data);
    }

    /**
     * list order penjualan by no_po
     * @param=no_po
     */
    function order_by_po(){
        $no_po=$this->input->get('no_po');

        $order=$this->M_penjualan->list_order_by_po($no_po)->result();

        echo json_encode($order);
    }

    /**
     * hapus order penjualan by id
     */
    function hapus_order_penjualan_by_id(){
        $id=$this->input->post('id');

        $hapus=$this->M_penjualan->hapus_order_penjualan_by_id($id);

        if($hapus){
            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil dihapus'
            );
        }else{
            $data=array(
                'success'=>false,
                'pesan'=>'Data gagal dihapus'
            );
        }

        echo json_encode($data);
    }

    /**
     * detail order by id
     */
    function no_by_id(){
        $no_po=$this->input->get('no_po');

        $detail=$this->M_penjualan->no_by_id($no_po)->row_array();

        echo json_encode($detail);
    }

    function tampil_kode(){
      $kode=$this->M_penjualan->get_code('01');

      echo $kode;
    }
}
