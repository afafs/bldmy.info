<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\BasicSetting;
use App\Models\User\UserQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Image;

class QrController extends Controller
{
    public function index()
    {
        $data['qrcodes'] = UserQrCode::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('user.qr.index', $data);
    }

    public function qrCode()
    {
        $bs = BasicSetting::firstOrCreate([
            'user_id' => Auth::user()->id
        ]);

        if (empty($bs->qr_image) || !file_exists(public_path('assets/front/img/user/qr/' . $bs->qr_image))) {
            $directory = public_path('assets/front/img/user/qr/');
            @mkdir($directory, 0775, true);
            $fileName = uniqid() . '.png';

            \QrCode::size(250)->errorCorrection('H')
                ->color(0, 0, 0)
                ->format('png')
                ->style('square')
                ->eye('square')
                ->generate(url(Auth::user()->username), $directory . $fileName);


            $bs->qr_image = $fileName;
            $bs->qr_url = url(Auth::user()->username);
            $bs->save();
        }

        $data['abs'] = $bs;

        return view('user.qr.generate', $data);
    }

    public function generate(Request $request)
    {
        if (!$request->filled('url')) {
            return "url_empty";
        }

        $img = $request->file('image');
        $type = $request->type;

        $bs = BasicSetting::where('user_id', Auth::user()->id)->first();

        // set default values for all params of qr image, if there is no value for a param
        $color = hex2rgb($request->color);

        $directory = public_path('assets/front/img/user/qr/');
        @mkdir($directory, 0775, true);
        $qrImage = uniqid() . '.png';

        // remove previous qr image
        @unlink($directory . $bs->qr_image);

        // new QR code init
        $qrcode = \QrCode::size($request->size)->errorCorrection('H')->margin($request->margin)
            ->color($color['red'], $color['green'], $color['blue'])
            ->format('png')
            ->style($request->style)
            ->eye($request->eye_style);

        if ($type == 'image' && $request->hasFile('image')) {

            @unlink($directory . $bs->qr_inserted_image);
            $mergedImage = uniqid() . '.' . $img->getClientOriginalExtension();
            $img->move($directory, $mergedImage);
        }



        // generating & saving the qr code in folder
        $qrcode->generate($request->url, $directory . $qrImage);



        // calcualte the inserted image size
        $qrSize = $request->size;


        if ($type == 'image') {
            $imageSize = $request->image_size;
            $insertedImgSize = ($qrSize * $imageSize) / 100;


            // inserting image using Image Intervention & saving the qr code in folder
            if ($request->hasFile('image')) {

                $qr = Image::make($directory . $qrImage);
                $logo = Image::make($directory . $mergedImage);
                $logo->resize(null, $insertedImgSize, function ($constraint) {
                    $constraint->aspectRatio();
                });


                $logoWidth = $logo->width();
                $logoHeight = $logo->height();


                $qr->insert($logo, 'top-left', (int) (((($qrSize - $logoWidth) * $request->image_x) / 100)), (int) (((($qrSize - $logoHeight) * $request->image_y) / 100)));
                $qr->save($directory . $qrImage);
            } else {

                if (!empty($bs->qr_inserted_image) && file_exists('./' . $directory . $bs->qr_inserted_image)) {

                    $qr = Image::make($directory . $qrImage);
                    $logo = Image::make($directory . $bs->qr_inserted_image);
                    $logo->resize(null, $insertedImgSize, function ($constraint) {
                        $constraint->aspectRatio();
                    });


                    $logoWidth = $logo->width();
                    $logoHeight = $logo->height();


                    $qr->insert($logo, 'top-left', (int) (((($qrSize - $logoWidth) * $request->image_x) / 100)), (int) (((($qrSize - $logoHeight) * $request->image_y) / 100)));

                    $qr->save($directory . $qrImage);
                }
            }
        }



        if ($type == 'text') {
            $imageSize = $request->text_size;
            $insertedImgSize = ($qrSize * $imageSize) / 100;

            $logo = Image::canvas($request->text_width, $insertedImgSize, "#ffffff")->text($request->text, 0, 0, function ($font) use ($request, $insertedImgSize) {
                $font->file(public_path('assets/front/fonts/Lato-Regular.ttf'));
                $font->size($insertedImgSize);
                $font->color('#' . $request->text_color);
                $font->align('left');
                $font->valign('top');
            });

            $logoWidth = $logo->width();
            $logoHeight = $logo->height();

            $qr = Image::make($directory . $qrImage);

            // use callback to define details
            $qr->insert($logo, 'top-left', (int) (((($qrSize - $logoWidth) * $request->text_x) / 100)), (int) (((($qrSize - $logoHeight) * $request->text_y) / 100)));
            $qr->save($directory . $qrImage);
        }


        $bs->qr_color = $request->color;
        $bs->qr_size = $request->size;
        $bs->qr_style = $request->style;
        $bs->qr_eye_style = $request->eye_style;
        $bs->qr_image = $qrImage;
        $bs->qr_type = $type;

        if ($type == 'image') {
            if ($request->hasFile('image')) {
                $bs->qr_inserted_image = $mergedImage;
            }
            $bs->qr_inserted_image_size = $imageSize;
            $bs->qr_inserted_image_x = $request->image_x;
            $bs->qr_inserted_image_y = $request->image_y;
        }

        if ($type == 'text' && !empty($request->text)) {
            $bs->qr_text = $request->text;
            $bs->qr_text_color = $request->text_color;
            $bs->qr_text_size = $request->text_size;
            $bs->qr_text_x = $request->text_x;
            $bs->qr_text_y = $request->text_y;
        }

        $bs->qr_margin = $request->margin;
        $bs->qr_url = $request->url;
        $bs->save();

        return asset('assets/front/img/user/qr/' . $qrImage);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required|max:255'
        ];

        $request->validate($rules);

        $bs = BasicSetting::where('user_id', Auth::user()->id)->first();

        $qrcode = new UserQrCode;
        $qrcode->user_id = Auth::user()->id;
        $qrcode->name = $request->name;
        $qrcode->image = $bs->qr_image;
        $qrcode->url = $bs->qr_url;
        $qrcode->save();

        $this->clearFilters($bs);

        Session::flash('success', toastrMsg('Store_successfully!'));
        return back();
    }

    public function clear()
    {
        $bs = BasicSetting::where('user_id', Auth::user()->id)->first();
        $this->clearFilters($bs, 'clear');

        Session::flash('success', toastrMsg('Cleared_all_filters'));
        return back();
    }

    public function clearFilters($bs, $type = NULL)
    {
        @unlink(public_path('assets/front/img/user/qr/' . $bs->qr_inserted_image));
        if ($type == 'clear') {
            @unlink(public_path('assets/front/img/user/qr/' . $bs->qr_image));
        }

        $bs->qr_image = NULL;
        $bs->qr_color = '000000';
        $bs->qr_size = 250;
        $bs->qr_style = 'square';
        $bs->qr_eye_style = 'square';
        $bs->qr_margin = 0;
        $bs->qr_text = NULL;
        $bs->qr_text_color = '000000';
        $bs->qr_text_size = 15;
        $bs->qr_text_x = 50;
        $bs->qr_text_y = 50;
        $bs->qr_inserted_image = NULL;
        $bs->qr_inserted_image_size = 20;
        $bs->qr_inserted_image_x = 50;
        $bs->qr_inserted_image_y = 50;
        $bs->qr_type = 'default';
        $bs->qr_url = NULL;
        $bs->save();
    }

    public function delete(Request $request)
    {
        $qrcode = UserQrCode::where('user_id', Auth::user()->id)->where('id', $request->qrcode_id)->firstOrFail();
        @unlink(public_path('assets/front/img/user/qr/' . $qrcode->image));
        $qrcode->delete();

        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $qrcode = UserQrCode::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();
            @unlink(public_path('assets/front/img/user/qr/' . $qrcode->image));
            $qrcode->delete();
        }
        Session::flash('success', toastrMsg('Bulk_Deleted_successfully!'));
        return "success";
    }
}
