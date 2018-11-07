

<?php if(!defined('BASEPATH')) exit('Akses langsung tidak diperbolehkan');

class Sample_form extends CI_Controller {
    function Sample_form()
// fungsi constructor
    {
        parent::__construct();
    }
    function index()
// fungsi pada waktu class ini diload
    {
        $this->load->helper('url');
        $this->load->view('sample_form_view');
    }
    function ambil_kota()
// fungsi untuk mengambil nama-nama kota berdasarkan id provinsi yang diambil dari data POST
    {
        switch($this->input->post('id_prov')) {
        case '1':
            $kota =
                array
                (
                    array
                    (
                        'id' => '1',
                        'nama' => 'Bandung'
                    ),
                    array
                    (
                        'id' => '2',
                        'nama' => 'Tasikmalaya'
                    )
                );
            break;
        case '2':
            $kota =
                array
                (
                    array
                    (
                        'id' => '3',
                        'nama' => 'Cilacap'
                    ),
                    array
                    (
                        'id' => '2',
                        'nama' => 'Solo'
                    )
                );
            break;
        };

// masukan id_kota dan nama_kota yang dipilih oleh user ke dalam array $hasil[i]
        $i=0;
        foreach ($kota as $rows) {
            $hasil[$i]['id_kota'] = $rows['id'];
            $hasil[$i]['nama_kota'] = $rows['nama'];
            $i++;
        };
        $this->output->set_output(json_encode($hasil));
    }

}
?>
