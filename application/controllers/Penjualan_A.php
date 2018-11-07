<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan extends CI_Controller {

	/**
	 * @author : Ahmad Kurosaki
	 * @web : 
	 * @keterangan : Controller untuk halaman profil
	 **/
	
	public function index()
	{
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

			
			$d['judul']="Penjualan";
			
			//paging
			$page=$this->uri->segment(3);
			$limit=$this->config->item('limit_data');
			if(!$page):
			$offset = 0;
			else:
			$offset = $page;
			endif;
			
			$text = "SELECT * FROM order_penjualan $where ";		
			$tot_hal = $this->app_model->manualQuery($text);		
			
			$d['tot_hal'] = $tot_hal->num_rows();
			
			$config['base_url'] = site_url() . '/penjualan/index/';
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
			

			$text = "SELECT * FROM order_penjualan $where 
					ORDER BY no_po DESC,tgl_po DESC 
					LIMIT $limit OFFSET $offset";
			$d['data'] = $this->app_model->manualQuery($text);
			
			$text = "SELECT * FROM rekening ORDER BY no_rek ASC";
			$d['list_rek'] = $this->app_model->manualQuery($text);
			
			$sql = "SELECT a.no_po,a.tgl_po,a.tgl_pemasangan,a.id_client,b.nama_client FROM 
					po_intern a left join client b on a.id_client=b.id_client where a.ket=1 ORDER BY a.no_po ASC";
			$d['list_po'] = $this->app_model->manualQuery($sql);
			
			
			$d['content'] = $this->load->view('penjualan/vpenjualan', $d, true);		
			$this->load->view('home',$d);
		}else{
			header('location:'.base_url());
		}
	}
	
	
	public function edit()
	{		
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
			
			$id = $this->input->post('id');  
			$text = "SELECT a.*,b.nama_client FROM order_penjualan a left join client b on a.id_client=b.id_client left join po_intern c on a.no_po=c.no_po WHERE a.no_po='$id' and c.ket=2 LIMIT 1";
			$data = $this->app_model->manualQuery($text);
			foreach($data->result() as $db){
				$d['no_po']				=$db->no_po;
				$d['id_client']			=$db->id_client;
				$d['nama_client']		=$db->nama_client;
				$d['tgl_po']			=$db->tgl_po;
				$d['tgl_pemasangan']	=$db->tgl_pemasangan;
				echo json_encode($d);
			}

		}else{
			header('location:'.base_url());
		}
	}
	
	
	public function hapus()
	{
		$cek = $this->session->userdata('logged_in');		
		if(!empty($cek)){			
			$id = $this->uri->segment(3);
			var_dump($id);
			$text = "UPDATE po_intern set ket=1 WHERE no_po='$id'";
			$data = $this->app_model->manualQuery($text);

			$this->app_model->manualQuery("DELETE FROM order_penjualan WHERE no_po='$id'");
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/penjualan'>";	
			// echo $this->db->last_query();			
		}else{
			header('location:'.base_url());
		}
	}
	
	
	public function simpan()
	{		
	$this->db->last_query();
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
				$up['no_po']=$this->input->post('no_po');			
				$up['tgl_po']=$this->input->post('tgl_po');
				$up['tgl_pemasangan']=$this->input->post('tgl_pemasangan');
				$up['id_client']=$this->input->post('id_client');
				$up['detail_pesanan']=$this->input->post('detail_pesanan');
				$up['ukuran']=$this->input->post('ukuran');
				$up['jumlah']=$this->input->post('jumlah');
				$up['no_rek']=$this->input->post('no_rek');
				$up['harga']=str_replace(',','',$this->input->post('harga'));
				$up['ppn']=str_replace(',','',$this->input->post('ppn'));
				$up['total']=str_replace(',','',$this->input->post('total'));
				$up['input_by']=$this->session->userdata('username');
				$up['last_update']=date('Y-m-d h:m:s');
				$aa['ket']= 2;
				
				$id['no_po']=$this->input->post('no_po');
				$id['no_rek']=$this->input->post('no_rek');
				
				$ah['no_po']=$this->input->post('no_po');
				
				$no_po=$this->input->post('no_po');
				$no_rek=$this->input->post('no_rek');
				
				$text = "SELECT * FROM order_penjualan WHERE no_po='$no_po'";
				$data = $this->app_model->manualQuery($text);

				$this->app_model->updateData("po_intern",$aa,$ah);

				// if($data->num_rows()>0){
				// 	$this->app_model->updateData("order_penjualan",$up,$id);
				// 	echo 'Simpan data Sukses sdfgh';					
				// }else{
					$this->app_model->insertData("order_penjualan",$up);
					//echo $this->db->last_query();
					echo 'Simpan data Sukses';		
				// }
		}else{
				header('location:'.base_url());
		}
	
	}	
	
	public function DetailOrder()
	{
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
			$id = $this->input->post('no_po'); 			
			$text = "SELECT * FROM order_penjualan WHERE no_po='$id'";
			$d['data'] = $this->app_model->manualQuery($text);
			$this->load->view('penjualan/detail_penjualan',$d);	
			//echo $text;
		}else{
			header('location:'.base_url());
		}
	}
	
	public function hapusDetail()
	{
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
			$id = $this->input->post('id'); 
			//$rek = $this->input->post('no_rek'); 
			
			$text = "DELETE FROM order_penjualan WHERE id='$id'";
			$d['data'] = $this->app_model->manualQuery($text);
			
			$text = "SELECT * FROM order_penjualan WHERE no_po='$id'";
			$d['data'] = $this->app_model->manualQuery($text);
			
			$this->load->view('penjualan/detail_penjualan',$d);

		}else{
			header('location:'.base_url());
		}
	}
	
}

/* End of file profil.php */
/* Location: ./application/controllers/profil.php */