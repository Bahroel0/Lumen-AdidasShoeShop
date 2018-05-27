<?php

    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Produk;
    use Illuminate\Support\Facades\DB;

    class ProdukController extends Controller{
        public function addProduk(Request $request){
            
            $image = $request->file('image');
            $image_ext = $image->getClientOriginalExtension();
            $new_image_name = rand(123456,999999).".".$image_ext;
            $image->move('uploads/images', $new_image_name);

            $produk = new Produk;
            $produk->nama = $request->input('nama');
            $produk->harga = $request->input('harga');
            $produk->warna = $request->input('warna');
            $produk->ukuran = $request->input('ukuran');
            $produk->kategori = $request->input('kategori');
            $produk->deskripsi = $request->input('deskripsi');
            $produk->stok = $request->input('stok');
            $produk->rating = $request->input('rating');
            $produk->image_path = "/uploads/images/".$new_image_name;
            $produk->save();
            if($produk->save()){
                echo "success !";
            }
        }

        public function getAllProduk(){
            
        }



    }