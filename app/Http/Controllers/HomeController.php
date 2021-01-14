<?php

namespace App\Http\Controllers;

use App\Constant\ProjectStatusConstant;
use App\Constant\RoleConstant;
use App\Constant\SampleStatusConstant;
use App\Constant\WarningStatusConstant;

use App\Helper\RedirectionHelper;

use App\Models\Customer;
use App\Models\Partner;
use App\Models\Project;
use App\Models\ProjectCategory;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $expectedStage = RedirectionHelper::routeBasedOnRegistrationStage(route('home'));
        if ($expectedStage == route('home')) {
            $user = auth()->user();
            $role = $user->roles()->first()->name;
            switch ($role) {
                case RoleConstant::ADMINISTRATOR:
                    return $this->administratorDashboard($request, $user, $role);
                    break;
                case RoleConstant::CUSTOMER:
                    // TO DO: for next phase, uncomment this code
                    return $this->customerDashboard($request, $user, $role);
                    // return redirect()->route('warning', ['type' => WarningStatusConstant::WAITING_VALIDATION]); 
                    break;
                case RoleConstant::PARTNER:
                    // TO DO: for next phase, uncomment this code
                    return $this->partnerDashboard($request, $user, $role);
                    // return redirect()->route('warning', ['type' => WarningStatusConstant::WAITING_VALIDATION]); 
                    break;
                default:
                    return redirect()->route('warning', ['type' => WarningStatusConstant::CAN_NOT_ACCESS]);
            }
        }
        return redirect($expectedStage);
    }

    private function partnerDashboard(Request $request, $user, $role)
    {
        $partner = $user->partner()->first();
        
        $projectsAll = $partner->projects()
                        ->orderBy('created_at', 'desc')
                        ->get();

        $projectsRequest = $partner->projects()
                        ->orderBy('created_at', 'desc')
                        ->where('status', ProjectStatusConstant::PROJECT_OPENED)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_DEALT)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_DP_OK)
                        ->get();
        
        $projectsInProgress = $partner->projects()
                        ->orderBy('created_at', 'desc')
                        ->where('status', ProjectStatusConstant::PROJECT_WORK_IN_PROGRESS)
                        ->get();
        
        $projectsDone = $partner->projects()
                        ->orderBy('created_at', 'desc')
                        ->where('status', ProjectStatusConstant::PROJECT_FINISHED)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_FULL_PAYMENT_OK)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_SENT)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_DONE)
                        ->get();
        
        $projectsRejected = $partner->projects()
                        ->orderBy('created_at', 'desc')
                        ->where('status', ProjectStatusConstant::PROJECT_FAILED)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_CANCELED)
                        ->get();

        $samplesAll =  $partner->transactions()
                        ->orderBy('created_at', 'desc')
                        ->with('sample', 'project')
                        ->whereHas('sample')
                        ->get();

        $samplesRequest = $partner->transactions()
                        ->orderBy('created_at', 'desc')
                        ->with('sample', 'project')
                        ->whereHas('sample', function($query) {
                            $query->where('status', SampleStatusConstant::SAMPLE_WAIT_PAYMENT)
                                ->orWhere('status', SampleStatusConstant::SAMPLE_PAYMENT_OK);
                        })
                        ->get();
        
        $samplesInProgress = $partner->transactions()
                        ->orderBy('created_at', 'desc')
                        ->with('sample', 'project')
                        ->whereHas('sample', function($query) {
                            $query->where('status', SampleStatusConstant::SAMPLE_WORK_IN_PROGRESS);
                        })
                        ->get();
        
        $samplesDone = $partner->transactions()
                        ->orderBy('created_at', 'desc')
                        ->with('sample', 'project')
                        ->whereHas('sample', function($query) {
                            $query->where('status', SampleStatusConstant::SAMPLE_FINISHED)
                                ->orWhere('status', SampleStatusConstant::SAMPLE_SENT)
                                ->orWhere('status', SampleStatusConstant::SAMPLE_APPROVED);
                        })
                        ->get();
        
        $samplesRejected = $partner->transactions()
                        ->orderBy('created_at', 'desc')
                        ->with('sample', 'project')
                        ->whereHas('sample', function($query) {
                            $query->where('status', SampleStatusConstant::SAMPLE_REJECTED);
                        })
                        ->get();
        
        $categories = ProjectCategory::all();

        return view('pages.partner.dashboard', get_defined_vars());
    }

    private function customerDashboard(Request $request, $user, $role)
    {
        $customer = $user->customer()->first();

        $projectsAll = $customer->projects()
                        ->orderBy('created_at', 'desc')
                        ->get();

        $projectsRequest = $customer->projects()
                        ->orderBy('created_at', 'desc')
                        ->where('status', ProjectStatusConstant::PROJECT_OPENED)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_DEALT)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_DP_OK)
                        ->get();
        
        $projectsInProgress = $customer->projects()
                        ->orderBy('created_at', 'desc')
                        ->where('status', ProjectStatusConstant::PROJECT_WORK_IN_PROGRESS)
                        ->get();
        
        $projectsDone = $customer->projects()
                        ->orderBy('created_at', 'desc')
                        ->where('status', ProjectStatusConstant::PROJECT_FINISHED)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_FULL_PAYMENT_OK)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_SENT)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_DONE)
                        ->get();
        
        $projectsRejected = $customer->projects()
                        ->orderBy('created_at', 'desc')
                        ->where('status', ProjectStatusConstant::PROJECT_FAILED)
                        ->orWhere('status', ProjectStatusConstant::PROJECT_CANCELED)
                        ->get();

        $samplesAll =  $customer->transactions()
                        ->orderBy('created_at', 'desc')
                        ->with('sample', 'project')
                        ->whereHas('sample')
                        ->get();

        $samplesRequest = $customer->transactions()
                        ->orderBy('created_at', 'desc')
                        ->with('sample', 'project')
                        ->whereHas('sample', function($query) {
                            $query->where('status', SampleStatusConstant::SAMPLE_WAIT_PAYMENT)
                                ->orWhere('status', SampleStatusConstant::SAMPLE_PAYMENT_OK);
                        })
                        ->get();
        
        $samplesInProgress = $customer->transactions()
                        ->orderBy('created_at', 'desc')
                        ->with('sample', 'project')
                        ->whereHas('sample', function($query) {
                            $query->where('status', SampleStatusConstant::SAMPLE_WORK_IN_PROGRESS);
                        })
                        ->get();
        
        $samplesDone = $customer->transactions()
                        ->orderBy('created_at', 'desc')
                        ->with('sample', 'project')
                        ->whereHas('sample', function($query) {
                            $query->where('status', SampleStatusConstant::SAMPLE_FINISHED)
                                ->orWhere('status', SampleStatusConstant::SAMPLE_SENT)
                                ->orWhere('status', SampleStatusConstant::SAMPLE_APPROVED);
                        })
                        ->get();
        
        $samplesRejected = $customer->transactions()
                        ->orderBy('created_at', 'desc')
                        ->with('sample', 'project')
                        ->whereHas('sample', function($query) {
                            $query->where('status', SampleStatusConstant::SAMPLE_REJECTED);
                        })
                        ->get();
        
        $categories = ProjectCategory::all();

        return view('pages.customer.dashboard', get_defined_vars());
    }

    private function administratorDashboard(Request $request, $user, $role)
    {
        $customers = Customer::all();
        $partners = Partner::all();
        $projects = Project::all();
        $categories = ProjectCategory::all();

        return view('pages.administrator.dashboard', get_defined_vars());
    }
}
