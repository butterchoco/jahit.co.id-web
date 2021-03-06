<?php

namespace App\Http\Controllers;

use App\Constant\ChatTemplateConstant;
use App\Constant\ProjectStatusConstant;
use App\Constant\MaterialRequestStatusConstant;
use App\Constant\RoleConstant;
use App\Constant\SampleStatusConstant;
use App\Constant\TransactionConstant;
use App\Constant\WarningStatusConstant;

use App\Helper\FileHelper;
use App\Helper\RedirectionHelper;

use App\Models\Chat;
use App\Models\Customer;
use App\Models\Inbox;
use App\Models\InvoiceFile;
use App\Models\Material;
use App\Models\Partner;
use App\Models\Project;
use App\Models\MaterialRequest;
use App\Models\MouFile;
use App\Models\Transaction;
use App\Models\User;

use Illuminate\Http\Request;

class AdministratorController extends Controller
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
     * Show the admin's dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function verification(Request $request)
    {
        $expectedStage = RedirectionHelper::routeBasedOnRegistrationStage(route('home.transaction'));
        if ($expectedStage == route('home.transaction')) {
            if ($role == RoleConstant::ADMINISTRATOR) {

                $paymentSlips = PaymentSlip::orderBy('updated_at', 'desc')->get();
                return view('pages.administrator.paymentVerification', get_defined_vars());

            } else {
                return redirect()->route('warning', ['type' => WarningStatusConstant::CAN_NOT_ACCESS]);
            }
        }
        return redirect($expectedStage);
    }
    
    public function paymentVerification(Request $request)
    {
        $expectedStage = RedirectionHelper::routeBasedOnRegistrationStage(route('home.transaction'));
        if ($expectedStage == route('home.transaction')) {
            $this->validate($request, [
                'transactionID' => [
                    'required',
                    'integer',
                    'min:1'
                ],
                'status' => [
                    'required',
                    'string'
                ],
                'mou_path' => [
                    'mimes:pdf',
                    'max:25000'
                ],
                'invoice_path' => [
                    'mimes:pdf',
                    'max:25000'
                ],
            ]);

            $user = auth()->user();
            $role = $user->roles()->first()->name;
            if ($role == RoleConstant::ADMINISTRATOR) {
                
                $transaction = Transaction::find($request->transactionID);

                $file_path_prefix = '/file/transaction/';

                if ($request->invoice_path != null) {
                    $invoiceFile = new InvoiceFile;
                    $invoiceFile->path = FileHelper::saveFileToPublic($request->file('invoice_path'), $file_path_prefix . 'invoice');
                    $invoiceFile->transaction_id = $request->transactionID;
                    $invoiceFile->save();
                }

                if ($request->mou_path != null) {
                    $mouFile = new MouFile;
                    $mouFile->path = FileHelper::saveFileToPublic($request->file('mou_path'), $file_path_prefix . 'mou');
                    $mouFile->transaction_id = $request->transactionID;
                    $mouFile->save();
                }

                switch ($request->status) {
                    case "WAITING":
                        $transaction->status = TransactionConstant::PAY_IN_VERIF;
                        if ($transaction->type == TransactionConstant::SAMPLE_TYPE) {
                            $sample = $transaction->sample;
                            $sample->status = SampleStatusConstant::SAMPLE_WAIT_PAYMENT;
                            $sample->save();
                        } else if ($transaction->type == TransactionConstant::DOWN_PAYMENT_TYPE) {
                            $project = $transaction->project;
                            $project->status = ProjectStatusConstant::PROJECT_DEALT;
                            $project->save();
                        } else if ($transaction->type == TransactionConstant::PELUNASAN_TYPE) {
                            $project = $transaction->project;
                            $project->status = ProjectStatusConstant::PROJECT_FINISHED;
                            $project->save();
                        }
                        break;

                    case "ACCEPT":
                        $transaction->status = TransactionConstant::PAY_OK;
                        if ($transaction->type == TransactionConstant::SAMPLE_TYPE) {
                            $sample = $transaction->sample;
                            $sample->status = SampleStatusConstant::SAMPLE_PAYMENT_OK;
                            $sample->save();
                        } else if ($transaction->type == TransactionConstant::DOWN_PAYMENT_TYPE) {
                            $project = $transaction->project;
                            $project->status = ProjectStatusConstant::PROJECT_DP_OK;
                            $project->save();
                        } else if ($transaction->type == TransactionConstant::PELUNASAN_TYPE) {
                            $project = $transaction->project;
                            $project->status = ProjectStatusConstant::PROJECT_FULL_PAYMENT_OK;
                            $project->save();
                        }
                        $inbox = Inbox::where("customer_id", $transaction->customer_id)
                                    ->where("partner_id", $transaction->partner_id)
                                    ->first();

                        $chatVerification = new Chat;
                        $chatVerification->role = ChatTemplateConstant::ADMIN_ROLE;
                        $chatVerification->type = ChatTemplateConstant::VERIFICATION_TYPE;
                        $chatVerification->inbox_id = $inbox->id;
                        $chatVerification->save();
                        break;

                    case "REJECT":
                        $transaction->status = TransactionConstant::PAY_FAIL;
                        if ($transaction->type == TransactionConstant::SAMPLE_TYPE) {
                            $sample = $transaction->sample;
                            $sample->status = SampleStatusConstant::SAMPLE_WAIT_PAYMENT;
                            $sample->save();
                        } else if ($transaction->type == TransactionConstant::DOWN_PAYMENT_TYPE) {
                            $project = $transaction->project;
                            $project->status = ProjectStatusConstant::PROJECT_DEALT;
                            $project->save();
                        } else if ($transaction->type == TransactionConstant::PELUNASAN_TYPE) {
                            $project = $transaction->project;
                            $project->status = ProjectStatusConstant::PROJECT_FULL_PAYMENT_FAIL;
                            $project->save();
                        }
                        $inbox = Inbox::where("customer_id", $transaction->customer_id)
                                    ->where("partner_id", $transaction->partner_id)
                                    ->first();

                        $chatVerification = new Chat;
                        $chatVerification->role = ChatTemplateConstant::ADMIN_ROLE;
                        $chatVerification->type = ChatTemplateConstant::VERIFICATION_REJECTED_TYPE;
                        $chatVerification->inbox_id = $inbox->id;
                        $chatVerification->save();
                        break;

                    default:
                        return redirect()->route('warning', ['type' => WarningStatusConstant::NOT_FOUND]);
                }
                $transaction->save();
            } else {
                return redirect()->route('warning', ['type' => WarningStatusConstant::CAN_NOT_ACCESS]);
            }
        }
        return redirect($expectedStage);
    }

    public function materialRequestVerificationPage(Request $request)
    {
        $expectedStage = RedirectionHelper::routeBasedOnRegistrationStage(route('home.material'));
        if ($expectedStage == route('home.material')) {
            $user = auth()->user();
            $role = $user->roles()->first()->name;
            $materials = Material::all();
            if ($role == RoleConstant::ADMINISTRATOR) {

                $requestsRequested = Project::whereHas('materialRequests', function($query) {
                                    $query->where('status', MaterialRequestStatusConstant::MATERIAL_REQUESTED);
                                })
                                ->with('materialRequests')
                                ->orderBy('updated_at', 'desc')
                                ->get();

                $requestsApproved = Project::whereHas('materialRequests', function($query) {
                                    $query->where('status', MaterialRequestStatusConstant::MATERIAL_APPROVED);
                                })
                                ->with('materialRequests')
                                ->orderBy('updated_at', 'desc')
                                ->get();

                $requestsSent = Project::whereHas('materialRequests', function($query) {
                                    $query->where('status', MaterialRequestStatusConstant::MATERIAL_SENT);
                                })
                                ->with('materialRequests')
                                ->orderBy('updated_at', 'desc')
                                ->get();

                $requestsRejected = Project::whereHas('materialRequests', function($query) {
                                    $query->where('status', MaterialRequestStatusConstant::MATERIAL_REJECTED);
                                })
                                ->with('materialRequests')
                                ->orderBy('updated_at', 'desc')
                                ->get();

                return view('pages.administrator.material', get_defined_vars());

            } else {
                return redirect()->route('warning', ['type' => WarningStatusConstant::CAN_NOT_ACCESS]);
            }
        }
        return redirect($expectedStage);
    }

    public function materialRequestVerification(Request $request)
    {
        $expectedStage = RedirectionHelper::routeBasedOnRegistrationStage(route('home.material'));
        if ($expectedStage == route('home.material')) {
            $this->validate($request, [
                'materialRequestID' => [
                    'required',
                    'integer',
                    'min:1'
                ],
                'status' => [
                    'required',
                    'string'
                ],
            ]);

            $user = auth()->user();
            $role = $user->roles()->first()->name;
            if ($role == RoleConstant::ADMINISTRATOR) {
                $materialRequest = MaterialRequest::find($request->materialRequestID);

                switch ($request->status) {
                    case "WAITING":
                        $materialRequest->status = MaterialRequestStatusConstant::MATERIAL_REQUESTED;
                        break;    
                    case "ACCEPT":
                        $materialRequest->status = MaterialRequestStatusConstant::MATERIAL_APPROVED;
                        break;
                    case "SENT":
                        $materialRequest->status = MaterialRequestStatusConstant::MATERIAL_SENT;
                        break;
                    case "REJECT":
                        $materialRequest->status = MaterialRequestStatusConstant::MATERIAL_REJECTED;
                        break;
                    default:
                        return redirect()->route('warning', ['type' => WarningStatusConstant::NOT_FOUND]);
                }
                $materialRequest->save();
            } else {
                return redirect()->route('warning', ['type' => WarningStatusConstant::CAN_NOT_ACCESS]);
            }
        }
        return redirect($expectedStage);
    }

    public function activateUser(Request $request)
    {
        $expectedStage = RedirectionHelper::routeBasedOnRegistrationStage(route('home'));
        if ($expectedStage == route('home')) {

            $user = auth()->user();
            $role = $user->roles()->first()->name;

            if ($role == RoleConstant::ADMINISTRATOR) {
                $this->validate($request, [
                    'userID' => [
                        'required',
                        'integer',
                        'min:1'
                    ],
                ]);

                $targetUser = User::find($request->userID);
                $targetUser->is_active = true;
                $targetUser->save();

            } else {
                return redirect()->route('warning', ['type' => WarningStatusConstant::CAN_NOT_ACCESS]);
            }
        }
        return redirect($expectedStage);
    }

    public function deactivateUser(Request $request)
    {
        $expectedStage = RedirectionHelper::routeBasedOnRegistrationStage(route('home'));
        if ($expectedStage == route('home')) {

            $user = auth()->user();
            $role = $user->roles()->first()->name;

            if ($role == RoleConstant::ADMINISTRATOR) {
                $this->validate($request, [
                    'userID' => [
                        'required',
                        'integer',
                        'min:1'
                    ],
                ]);

                $targetUser = User::find($request->userID);
                $targetUser->is_active = false;
                $targetUser->save();

            } else {
                return redirect()->route('warning', ['type' => WarningStatusConstant::CAN_NOT_ACCESS]);
            }
        }
        return redirect($expectedStage);
    }

    public function payProject(Request $request)
    {
        $expectedStage = RedirectionHelper::routeBasedOnRegistrationStage(route('home'));
        if ($expectedStage == route('home')) {

            $user = auth()->user();
            $role = $user->roles()->first()->name;

            if ($role == RoleConstant::ADMINISTRATOR) {
                $this->validate($request, [
                    'projectID' => [
                        'required',
                        'integer',
                        'min:1'
                    ],
                ]);

                $project = Project::find($request->projectID);
                $project->status = ProjectStatusConstant::PROJECT_DONE;
                $project->save();

            } else {
                return redirect()->route('warning', ['type' => WarningStatusConstant::CAN_NOT_ACCESS]);
            }
        }
        return redirect($expectedStage);
    }
}