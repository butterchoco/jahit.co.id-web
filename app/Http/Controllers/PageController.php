<?php

namespace App\Http\Controllers;

use App\Constant\WarningStatusConstant;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function homepage(Request $request)
    {
        return view('pages.homepage', get_defined_vars());
    }

    public function aboutpage(Request $request)
    {
        return view('pages.aboutpage', get_defined_vars());
    }

    public function choicePage(Request $request)
    {
        return view('pages.choicePage', get_defined_vars());
    }

    public function userLoginPage(Request $request)
    {
        return view('pages.userLoginPage', get_defined_vars());
    }

    public function userRegisterPage(Request $request)
    {
        return view('pages.userRegisterPage', get_defined_vars());
    }

    public function userCustomerRegisterPage(Request $request)
    {
        return view('pages.userCustomerRegisterPage', get_defined_vars());
    }
    
    public function userCustomerAddProjectPage(Request $request)
    {
        return view('pages.userCustomerAddProjectPage', get_defined_vars());
    }
    public function userCustomerProjectPage(Request $request)
    {
        return view('pages.userCustomerProjectPage', get_defined_vars());
    }
    public function userProjectDetailPage(Request $request)
    {
        return view('pages.userProjectDetailPage', get_defined_vars());
    }
    public function userCustomerTransactionPage(Request $request)
    {
        return view('pages.userCustomerTransactionPage', get_defined_vars());
    }
    public function userPartnerTransactionPage(Request $request)
    {
        return view('pages.userPartnerTransactionPage', get_defined_vars());
    }
    public function userPartnerAddMaterialPage(Request $request)
    {
        return view('pages.userPartnerAddMaterialPage', get_defined_vars());
    }
    public function userCustomerChatPage(Request $request)
    {
        return view('pages.userCustomerChatPage', get_defined_vars());
    }

    public function userPartnerRegisterPage(Request $request)
    {
        return view('pages.userPartnerRegisterPage', get_defined_vars());
    }
    public function userPartnerChatPage(Request $request)
    {
        return view('pages.userPartnerChatPage', get_defined_vars());
    }
    public function adminChat(Request $request)
    {
        return view('pages.adminChat', get_defined_vars());
    }
    public function adminTransaction(Request $request)
    {
        return view('pages.adminTransaction', get_defined_vars());
    }
    public function adminMaterial(Request $request)
    {
        return view('pages.adminMaterial', get_defined_vars());
    }

    public function notfound(Request $request)
    {
        return view('layouts._customPage', get_defined_vars());
    }
        public function warning(Request $request, $type)
    {
        $warningPicturePath = 'img/warning/';

        switch ($type) {
            case WarningStatusConstant::WAITING_VALIDATION:
                $picture = $warningPicturePath . 'waiting.gif';
                $text = 'Datamu sedang kami validasi. Mohon tunggu kontak dari tim kami..';
                $title = 'Menunggu Validasi';
                break;
            case WarningStatusConstant::CAN_NOT_ACCESS:
                $picture = $warningPicturePath . 'can_not_access.gif';
                $text = 'Oops! Kamu tidak memiliki akses ke halaman ini.. Pastikan kamu sudah login ke akunmu yaa';
                $title = 'Tidak Memiliki Akses';
                break;
            case WarningStatusConstant::NOT_FOUND:
                $picture = $warningPicturePath . 'not_found.gif';
                $text = 'Wah.. url atau data yang kamu pilih tidak ada.. Coba dicek lagi yaa';
                $title = 'Tidak Ada Halaman atau Data';
                break;
            case WarningStatusConstant::WORK_IN_PROGRESS:
                $picture = $warningPicturePath . 'work_in_progress.gif';
                $text = 'Mohon maaf. Halaman ini sedang dikerjakan..';
                $title = 'Halaman Sedang Dikerjakan';
                break;
            default:
                return redirect()->route('warning', ['type' => WarningStatusConstant::NOT_FOUND]);
        
        }
        return view('layouts.warning', get_defined_vars());        
    }
}