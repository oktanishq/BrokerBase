<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with real data
     */
    public function index()
    {
        // Get current month boundaries
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Total Properties count
        $totalProperties = Property::withoutTrashed()->count();

        // Active Listings count (status = 'available')
        $activeListings = Property::withoutTrashed()
            ->where('status', Property::STATUS_AVAILABLE)
            ->count();

        // Properties Sold count (status = 'sold')
        $propertiesSold = Property::withoutTrashed()
            ->where('status', Property::STATUS_SOLD)
            ->count();

        // Draft Properties count
        $draftProperties = Property::withoutTrashed()
            ->where('status', Property::STATUS_DRAFT)
            ->count();

        // Month-over-month comparisons
        $thisMonthProperties = Property::withoutTrashed()
            ->where('created_at', '>=', $startOfThisMonth)
            ->count();

        $lastMonthProperties = Property::withoutTrashed()
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        $thisMonthSold = Property::withoutTrashed()
            ->where('status', Property::STATUS_SOLD)
            ->where('created_at', '>=', $startOfThisMonth)
            ->count();

        $lastMonthSold = Property::withoutTrashed()
            ->where('status', Property::STATUS_SOLD)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // Calculate percentage changes
        $totalGrowthPercent = $lastMonthProperties > 0 
            ? round((($thisMonthProperties - $lastMonthProperties) / $lastMonthProperties) * 100, 1)
            : ($thisMonthProperties > 0 ? 100 : 0);

        $activeGrowthPercent = $lastMonthProperties > 0 
            ? round((($thisMonthProperties - $lastMonthProperties) / $lastMonthProperties) * 100, 1)
            : ($thisMonthProperties > 0 ? 100 : 0);

        $soldGrowthCount = $thisMonthSold - $lastMonthSold;

        // Recently Updated (latest 5 properties by updated_at - shows last listed or edited)
        $recentlyUpdated = Property::withoutTrashed()
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProperties',
            'activeListings',
            'propertiesSold',
            'draftProperties',
            'totalGrowthPercent',
            'activeGrowthPercent',
            'soldGrowthCount',
            'recentlyUpdated'
        ));
    }
}
