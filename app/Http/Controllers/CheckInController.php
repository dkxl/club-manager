<?php
namespace App\Http\Controllers;

use App\Http\Requests\CheckInRequest;
use App\Models\CheckIn;
use App\Models\Member;

class CheckInController extends BaseController
{

    /**
     * Display recent checkins for the member
     *
     * @param Member $member
     * @return \Illuminate\Http\Response
     */
    public function index(Member $member)
    {

        return view('checkins.member_visits', [
            'visits' => CheckIn::forMember($member)
                                ->orderBy('created_at', 'desc')
                                ->limit(50)
                                ->get(),
        ]);

    }

    /**
     * Return a form to create a new checkin request
     * @param Member $member
     */
    public function create(Member $member)
    {
        return view('checkins.form')->with('member', $member);
    }


    /**
     * Store a CheckIn attempt
     */
    public function store (CheckInRequest $request)
    {
        $checkin = new CheckIn($request->validated());
        $checkin->handleCheckInRequest();
        $checkin->save();

        if ($checkin->permitted) {
            return view('status.info',[ 'message' => "Check In OK" ]);
        } else {
            return view('status.warning',[ 'message' => "Check In Failed: " . $checkin->reason ]);
        }

    }


    /**
     * Display all checkins for the date
     * If the date is not specified, defaults to today()*
     * GET visits/day/{the_date}
     *
     * @param string $the_date (optional)
     * @return \Illuminate\Http\Response
     */
    public function day($the_date=null)
    {

        $visits = MemberVisits::day($the_date);   // defaults to today if the_date is empty

        if (count($visits > 0)) {
            return view('tables.daily_visits', [ 'visits' => $visits ]);
        } else {
            return view('components.panel.info',['message' => 'No Check Ins to display']);
        }


    }


    /**
     * Display checkin statistics for today
     * GET visits/totals/
     *
     * @return string
     */
    public function totals()
    {

        $visits_today = MemberVisits::day();

        $ok_members = MemberStatus::ok()->count();

        return  count($visits_today) . '/'  . $ok_members;

    }



    /**
     * All available visit statistics for the member
     * @param string $member_uuid
     * @return object $statistics
     */
    public function statistics(string $member_uuid)
    {

        $statistics = (object) ['last_visit', 'this_month', 'last_month'];

        $statistics->last_visit = self::lastVisit($member_uuid);

        $statistics->this_month = self::thisMonth($member_uuid);

        $statistics->last_month = self::lastMonth($member_uuid);

        return $statistics;
    }



    /**
     * Front of House statistics for the member
     * For use with the FOH check in screen, saves time by only calculating
     * stats that will be displayed
     * @param string $member_uuid
     * @return object $statistics
     */
    public function fohStats(string $member_uuid)
    {

        $statistics = (object) ['this_month', 'last_month'];

        $statistics->this_month = self::thisMonth($member_uuid);

        $statistics->last_month = self::lastMonth($member_uuid);

        return $statistics;
    }




}
