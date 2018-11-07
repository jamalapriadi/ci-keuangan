<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends CI_Controller {

  /**
   * @author : Ahmad Kurosaki
   * @web : 
   * @keterangan : Controller untuk halaman marketing
   **/

  public function index()
  {
    $cek = $this->session->userdata('logged_in');
    if(!empty($cek)){
      $cari = $this->input->post('txt_cari');
      if(empty($cari)){
        $where = ' ';
      }else{
        $where = " WHERE id_client LIKE '%$cari%' OR nama_client LIKE '%$cari%'";
      }

      $d['prg']= $this->config->item('prg');
      $d['web_prg']= $this->config->item('web_prg');

      $d['nama_program']= $this->config->item('nama_program');
      $d['instansi']= $this->config->item('instansi');
      $d['usaha']= $this->config->item('usaha');
      $d['alamat_instansi1']= $this->config->item('alamat_instansi1');
	  $d['alamat_instansi2']= $this->config->item('alamat_instansi2');
	  $d['kodeclient'] = $this->app_model->KodeClient();

      $d['judul']="Client";

      //paging
      $page=$this->uri->segment(3);
      $limit=$this->config->item('limit_data');
      if(!$page):
      $offset = 0;
      else:
      $offset = $page;
      endif;

      $text = "SELECT * FROM client $where ";
      $tot_hal = $this->app_model->manualQuery($text);

      $d['tot_hal'] = $tot_hal->num_rows();

      $config['base_url'] = site_url() . '/client/index/';
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


      $text = "SELECT * FROM client $where
          ORDER BY id_client ASC
          LIMIT $limit OFFSET $offset";
      $d['data'] = $this->app_model->manualQuery($text);

      //$text = "SELECT * FROM rekening";
      //$d['list'] = $this->app_model->manualQuery($text);


      $d['content'] = $this->load->view('master/client', $d, true);
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

      $d['judul']="Users";



      $d['content'] = $this->load->view('master/client', $d, true);
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
      $text = "SELECT * FROM client WHERE id_client='$id'";
      $data = $this->app_model->manualQuery($text);
      //if($data->num_rows() > 0){
        foreach($data->result() as $db){
		$d['id_client']		=$db->id_client;
        $d['nama_client']	=$db->nama_client;
        $d['alamat']		=$db->alamat;
		$d['telp']			=$db->telp;
        //  $d['username']		=$db->username;
        //  $d['nama']	=$db->nama_lengkap;
        //    $d['level']	=$db->level;
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
      $this->app_model->manualQuery("DELETE FROM client WHERE id_client='$id'");
      echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/client'>";
    }else{
      header('location:'.base_url());
    }
  }

  public function simpan()
  {

    $cek = $this->session->userdata('logged_in');
    if(!empty($cek)){

        $up['id_client']=$this->input->post('id_client');
        $up['nama_client']=$this->input->post('nama_client');
        $up['alamat']=$this->input->post('alamat');
		$up['telp']=$this->input->post('telp');
		$up['input_by']=$this->session->userdata('username');
		$up['last_update']=date('Y-m-d h:m:s');

        $id['id_client']=$this->input->post('id_client');

        $data = $this->app_model->getSelectedData("client",$id);
        if($data->num_rows()>0){
          $this->app_model->updateData("client",$up,$id);
          echo 'Update data Sukses';
        }else{
          $this->app_model->insertData("client",$up);
          echo 'Simpan data Sukses';
        }
    }else{
        header('location:'.base_url(master/client));
    }

  }

}

/* End of file profil.php */
/* Location: ./application/controllers/profil.php */
