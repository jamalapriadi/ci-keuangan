<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketing extends CI_Controller {

  /**
   * @author : Ahmad Kurosaki
   * @web : 
   * @keterangan : Controller untuk halaman marketing
   **/

  public function index()
  {
    $cek = $this->session->userdata('logged_in');
    if(!empty($cek)){
      $d['nama_program']= $this->config->item('nama_program');
      $d['instansi']= $this->config->item('instansi');
      $d['usaha']= $this->config->item('usaha');
      $d['alamat_instansi1']= $this->config->item('alamat_instansi1');
	    $d['alamat_instansi2']= $this->config->item('alamat_instansi2');
	    $d['kodeunik'] = $this->app_model->buat_kode();

      $d['judul']="Marketing";
      $d['content'] = $this->load->view('marketing/vutama', $d, true);
      $this->load->view('home',$d);
    }else{
      header('location:'.base_url());
    }
  }

  function vinyl_service(){
    $cek = $this->session->userdata('logged_in');
    if(!empty($cek)){
      $cari = $this->input->post('txt_cari');
      if(empty($cari)){
        $where = ' ';
      }else{
        $where = " WHERE no_po LIKE '%$cari%' OR nama_client LIKE '%$cari%'";
      }

      $d['prg']= $this->config->item('prg');
      $d['web_prg']= $this->config->item('web_prg');

      $d['nama_program']= $this->config->item('nama_program');
      $d['instansi']= $this->config->item('instansi');
      $d['usaha']= $this->config->item('usaha');
      $d['alamat_instansi1']= $this->config->item('alamat_instansi1');
	  $d['alamat_instansi2']= $this->config->item('alamat_instansi2');
	  $d['kodeunik'] = $this->app_model->buat_kode();

      $d['judul']="Marketing";
	
      //paging
      $page=$this->uri->segment(3);
      $limit=$this->config->item('limit_data');
      if(!$page):
      $offset = 0;
      else:
      $offset = $page;
      endif;

      $text = "SELECT * FROM po_intern $where ";
      $tot_hal = $this->app_model->manualQuery($text);

      $d['tot_hal'] = $tot_hal->num_rows();

      $config['base_url'] = site_url() . '/marketing/index/';
      $config['total_rows'] = $tot_hal->num_rows();
      $config['per_page'] = $limit;
      $config['uri_segment'] = 3;
      $config['next_link'] = 'Lanjut &raquo;';
      $config['prev_link'] = '&laquo; Kembali';
      $config['last_link'] = '<b>Terakhir &raquo; </b>';
      $config['first_link'] = '<b> &laquo; Pertama</b>';
      $this->pagination->initialize($config);
      $d["paginator"] =$this->pagination->create_links();
      $d['hal'] = $offset;


      $text = "SELECT * FROM po_intern $where
          ORDER BY no_po ASC
          LIMIT $limit OFFSET $offset";
      $d['data'] = $this->app_model->manualQuery($text);
	  
	  $client = "SELECT id_client,nama_client,alamat FROM client ORDER BY id_client ASC";
		$d['list_client'] = $this->app_model->manualQuery($client);

      //$text = "SELECT * FROM rekening";
      //$d['list'] = $this->app_model->manualQuery($text);


      $d['content'] = $this->load->view('marketing/vmarketing', $d, true);
      $this->load->view('home',$d);
    }else{
      header('location:'.base_url());
    }
  }

  public function tambah()
  {
    $cek = $this->session->userdata('logged_in');
    if(!empty($cek)){
      $d['prg']= $this->config->item('prg');
      $d['web_prg']= $this->config->item('web_prg');

      $d['nama_program']= $this->config->item('nama_program');
      $d['instansi']= $this->config->item('instansi');
      $d['usaha']= $this->config->item('usaha');
      $d['alamat_instansi']= $this->config->item('alamat_instansi');
      $d['judul']="Marketing";

      $d['content'] = $this->load->view('marketing/vmarketing', $d, true);
      $this->load->view('home',$d);
    }else{
      header('location:'.base_url());
    }
  }

  public function edit()
  {
    $cek = $this->session->userdata('logged_in');
    if(!empty($cek)){

      $id = $this->input->post('id');  //$this->uri->segment(3);
      $text = "SELECT * FROM po_intern WHERE no_po='$id'";
      $data = $this->app_model->manualQuery($text);
      //if($data->num_rows() > 0){
        foreach($data->result() as $db){
          $d['no_po']			=$db->no_po;
		  $d['tgl_po']			=$this->app_model->tgl_str($db->tgl_po);
		  $d['id_client']		=$db->id_client;
		  $d['no_spk']			=$db->no_spk;
		  $d['no_penawaran']	=$db->no_penawaran;
		  $d['tgl_penawaran']	=$this->app_model->tgl_str($db->tgl_penawaran);
		  $d['tgl_terima']		=$this->app_model->tgl_str($db->tgl_terima);
		  $d['pekerjaan']		=$db->pekerjaan;
		  $d['ukuran']			=$db->ukuran;
		  $d['jumlah']			=$db->jumlah;
		  $d['warna']			=$db->warna;
		  $d['bahan']			=$db->bahan;
		  $d['proses']			=$db->proses;
		  $d['ketentuan']		=$db->ketentuan;
		  $d['tgl_pemasangan']	=$this->app_model->tgl_str($db->tgl_pemasangan);
		  $d['alamat_pasang']	=$db->alamat_pasang;
		  $d['harga']			=$db->harga;
		  $d['total']			=$db->total;
		  //$d['username']		=$db->username;
          //$d['nama']	=$db->nama_lengkap;
            //$d['level']	=$db->level;
			//jalankan query
          echo json_encode($d);
		}
      //}

      //$d['content'] = $this->load->view('rekening/tambah', $d, true);
      //$this->load->view('home',$d);
    }else{
      header('location:'.base_url());
    }
  }

  public function hapus()
  {
    $cek = $this->session->userdata('logged_in');
    if(!empty($cek)){
      $id = $this->uri->segment(3);
      $this->app_model->manualQuery("DELETE FROM po_intern WHERE no_po='$id'");
      echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/marketing'>";
    }else{
      header('location:'.base_url());
    }
  }

  public function simpan()
  {
		
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
				
				$up['no_po']=$this->input->post('no_po');
				$up['tgl_po']=$this->app_model->tgl_sql($this->input->post('tgl_po'));
				$up['id_client']=$this->input->post('id_client');
				$up['no_spk']=$this->input->post('no_spk');
				$up['no_penawaran']=$this->input->post('no_penawaran');
				$up['tgl_penawaran']=$this->app_model->tgl_sql($this->input->post('tgl_penawaran'));
				$up['tgl_terima']=$this->app_model->tgl_sql($this->input->post('tgl_terima'));
				$up['pekerjaan']=$this->input->post('pekerjaan');
				$up['ukuran']=$this->input->post('ukuran');
				$up['jumlah']=$this->input->post('jumlah');
				$up['warna']=$this->input->post('warna');
				$up['bahan']=$this->input->post('bahan');
				$up['proses']=$this->input->post('proses');
				$up['ketentuan']=$this->input->post('ketentuan');
				$up['tgl_pemasangan']=$this->app_model->tgl_sql($this->input->post('tgl_pemasangan'));
				$up['alamat_pasang']=$this->input->post('alamat_pasang');
				$up['harga']=$this->input->post('harga');
				$up['ppn']=$this->input->post('10');
				$up['total']=$this->input->post('total');
				$up['input_by']=$this->session->userdata('username');
				$up['last_update']=date('Y-m-d h:m:s');
				//$up['debet']=str_replace(',','',$this->input->post('debet'));
				//$up['kredit']=str_replace(',','',$this->input->post('kredit'));
				//$up['tgl_insert']=date('Y-m-d h:m:s');
				
				
				$id['no_po']=$this->input->post('no_po');
				//$id['periode']=$this->input->post('periode');
				//$id['no_rek']=$this->input->post('no_rek');
				
				$data = $this->app_model->getSelectedData("po_intern",$id);
				if($data->num_rows()>0){
					$this->app_model->updateData("po_intern",$up,$id);
					echo 'Update data Sukses';
				}else{
					$this->app_model->insertData("po_intern",$up);
					echo 'Simpan data Sukses';		
				}
		}else{
				header('location:'.base_url());
		}
	
	}

}

/* End of file marketing.php */
/* Location: ./application/controllers/marketing.php */
