<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Deposit;

class DonationController extends Controller
{
    function index() {
        return $this->donationData('All Donations');
    }

    function initiated() {
        return $this->donationData('Initiated Donations', 'initiate');
    }

    function pending() {
        return $this->donationData('Pending Donations', 'pending');
    }

    function done() {
        return $this->donationData('Done Donations', 'done');
    }

    function rejected() {
        return $this->donationData('Rejected Donations', 'canceled');
    }

    protected function donationData($pageTitle, $scope = null) {
        $donations = Deposit::when($scope, fn($query) => $query->$scope())->whereNot('donation_receiver_id', ManageStatus::EMPTY)->whereNot('image_id', ManageStatus::EMPTY)->with(['donationReceiver','user', 'gateway'])->latest()->paginate(getPaginate());

        return view('admin.page.donations', compact('pageTitle', 'donations'));
    }
}
