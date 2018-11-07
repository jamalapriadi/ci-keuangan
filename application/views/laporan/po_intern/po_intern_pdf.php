<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .subjudulnya{
            color:#ad0000;
        }

        .subjudulnya::after{
			border-bottom: 1px dotted;
		}

        .subjudulnya::before{
			border-bottom: 1px dotted;
            /* content: "Read this -"; */
		}
        .garis_tepi2 {
            border-bottom: 1px dotted;
            /* margin-left:105px; */
            margin-left:37%;
        }
        table, th, td {
            height:20px;
        }
    </style>
</head>
<body>
    <?php 
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=po_intern.xls");
    ?>
    <div id="header" style="height:40px;">
        <div class="logo" style="float:left;width:30%">
            <img src="./asset/images/logo/logo-neonlite.png" alt="" style="height:80px;margin-top:-20px;">    
        </div>

        <div class="info-po" style="float:right;width:70%;height:100px;">
            <table style="width:100%">
                <tr>
                    <td></td>
                    <td style="color:red"><b>P.O INTERN</b></td>
                    <td style="color:red">ORDER</td>
                    <td style="color:red"> : <?php echo $marketing;?></td>
                </tr>
                <tr>
                    <td style="color:red">No.</td>
                    <td><b><?php echo $po['no_po'];?><b></td>
                    <td style="color:red">COPY</td>
                    <td style="color:red"> : 1</td>
                </tr>
            </table>
        </div>
    </div>

    <div style="clear:none"></div>
    <br><br>

    <table style="width:100%">
        <tr>
            <td style="width:30%;color:red;"><b>REFERENSI</b></td>
            <td> : </td>
        </tr>
        <tr>
            <td class="subjudulnya">
                Nama Pemesan
            </td>
            <td> :  <b style="margin-left:10px;"><?php echo $po['nama_client'];?></b></td>
        </tr>
    </table>
    <table style="width:100%">
        <tr>
            <td style="width:30%;" class="subjudulnya">Surat Pesanan / SPK.No</td>
            <td> : <span style="margin-left:10px;"><?php echo $po['no_spk'];?></span></td>
            <td class="subjudulnya">
                Tgl : <span style="color:#222"><?php echo date('d-M-Y',strtotime($po['tgl_po']));?></span>
            </td>
        </tr>
        <tr>
            <td class="subjudulnya">Surat Penawaran No.</td>
            <td> :  <span style="margin-left:10px;"><?php echo $po['no_penawaran'];?></span></td>
            <td class="subjudulnya">
                Tgl : <span style="color:#222"><?php echo date('d-M-Y',strtotime($po['tgl_penawaran']));?></span>
            </td>
        </tr>
        <tr>
            <td class="subjudulnya">Di terima di PT. Neonlite tgl</td>
            <td> : <span style="margin-left:10px;"><?php echo date('d-M-Y',strtotime($po['tgl_terima']));?></span></td>
            <td></td>
        </tr>
    </table>

    <br>

    <table style="width:100%">
        <tr>
            <td style="width:30%;color:red;"><b>SPESIFIKASI</b></td>
            <td style="width:3%"> : </td>
            <td></td>
        </tr>
        <tr>
            <td class="subjudulnya">Pesanan / Pekerjaan</td>
            <td style="width:3%"> : </td>
            <td>
                <?php echo $po['pekerjaan'];?>
            </td>
        </tr>
        <tr>
            <td class="subjudulnya">Ukuran</td>
            <td style="width:3%"> : </td>
            <td>
                <?php echo $po['ukuran'];?>
            </td>
        </tr>
        <tr>
            <td class="subjudulnya">Jumlah</td>
            <td style="width:3%"> : </td>
            <td>
                <?php echo $po['jumlah'];?>
            </td>
        </tr>
        <tr>
            <td class="subjudulnya">Warna</td>
            <td> : </td>
            <td>
                <?php echo $po['warna'];?>
            </td>
        </tr>
        <tr>
            <td class="subjudulnya">Bahan</td>
            <td> : </td>
            <td>
                <?php echo $po['bahan'];?>
            </td>
        </tr>
        <tr>
            <td class="subjudulnya">Proses</td>
            <td> : </td>
            <td>
                <?php echo $po['proses'];?>
            </td>
        </tr>
        <tr>
            <td class="subjudulnya">Ketentuan lain</td>
            <td style="width:3%"> : </td>
            <td>
                <?php echo $po['ketentuan'];?>
            </td>
        </tr>
    </table>

    <br>
    <br>
    <br>
    <br>

    <table style="width:100%">
        <tr>
            <td style="width:30%" class="subjudulnya">Cara Pembayaran</td>
            <td style="width:3%"> : </td>
            <td></td>
        </tr>
        <tr>
            <td class="subjudulnya">Proof</td>
            <td> : </td>
            <td></td>
        </tr>
    </table>

    <br>
    <br>
    <br>

    <table style="width:100%">
        <tr>
            <td style="width:30%" class="subjudulnya">Waktu Penyerahan / Pemasangan</td>
            <td style="width:3%"> : </td>
            <td> <?php echo $po['tgl_pemasangan'];?></td>
        </tr>
        <tr>
            <td class="subjudulnya">Alamat Penyerahan / Pemasangan</td>
            <td> : </td>
            <td> <?php echo $po['alamat_pasang'];?></td>
        </tr>
        <tr>
            <td class="subjudulnya">Harga Satuan</td>
            <td> : </td>
            <td>
                <?php echo number_format($po['harga']);?>
            </td>
        </tr>
        <tr>
            <td class="subjudulnya">Harga Total</td>
            <td> : </td>
            <td>
                <?php echo number_format($po['total']);?>
            </td>
        </tr>
    </table>

    <br><br>

    <table style="width:100%;text-align:center">
        <tr>
            <td class="subjudulnya">Dibuat</td>
            <td class="subjudulnya">Diperika</td>
            <td class="subjudulnya">Disetujui</td>
            <td class="subjudulnya">Jakarta, <span style="color:#222"><?php echo date('d-M-Y');?></span></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td class="subjudulnya"><b><?php echo $dibuat;?></b></td>
            <td class="subjudulnya"><b><?php echo $diperiksa;?></b></td>
            <td class="subjudulnya"><b><?php echo $disetujui;?></b></td>
            <td class="subjudulnya"><b>( <?php echo $marketing;?> )</b></td>
        </tr>
    </table>
    
</body>
</html>