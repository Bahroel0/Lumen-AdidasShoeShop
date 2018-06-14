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
            $produk = Produk::paginate(5);
            return response($produk);
        }

        public function getKategoriProduk(Request $request){
            $kategori = $request->get('kategori');
            $produk = Produk::where('kategori', $kategori)->paginate(3);
            return response($produk);
        }

        public function getDetailProduk(Request $request){
            $id = $request->get('id_produk');
            $produk = Produk::where('id', $id)->first();
            $res['success'] = true;
            $res['produk'] = $produk;
            return response($res);
        }

        public function find(Request $request){
            $searchkey = $request->get('searchkey');
            $produk = DB::table('tabel_produk')->where('nama','like','%'.$searchkey.'%')->paginate(3);
            return response($produk);
        }


        public function payment(Request $request){
            $id_produk = $request->get('id_produk');
            $jumlah_dibeli = $request->get('jumlah');
            
            $produk = Produk::where('id', $id_produk)->first();
            $stock = $produk->stok;

            if($stock){
                $produk->stok = $stock-$jumlah_dibeli;
                $produk->save();
                $res['success'] = true;
                $res['message'] = "Sukses dibeli";
                return response($res);
            }
        }


    }