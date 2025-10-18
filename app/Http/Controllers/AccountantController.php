<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use App\Models\Prescription;
use App\Models\MedicalFilePrescription;

class AccountantController extends Controller
{
    /**
     * Display the accountant dashboard
     */
    public function dashboard()
    {
        $accountant = Auth::user();

        if (!$accountant || !$accountant->hasRole('Accountant')) {
            abort(403, 'Unauthorized access');
        }

        // Financial statistics
        $totalRevenue = $this->calculateTotalRevenue();
        $monthlyRevenue = $this->calculateMonthlyRevenue();
        $pendingPayments = $this->getPendingPayments();
        $completedPayments = $this->getCompletedPayments();

        // Service statistics
        $serviceStats = $this->getServiceStatistics();
        
        // Recent transactions
        $recentTransactions = $this->getRecentTransactions();

        // Payment methods breakdown
        $paymentMethods = $this->getPaymentMethodsBreakdown();

        return view('accountant.dashboard', compact(
            'accountant',
            'totalRevenue',
            'monthlyRevenue',
            'pendingPayments',
            'completedPayments',
            'serviceStats',
            'recentTransactions',
            'paymentMethods'
        ));
    }

    /**
     * Display financial reports
     */
    public function reports()
    {
        $accountant = Auth::user();

        if (!$accountant || !$accountant->hasRole('Accountant')) {
            abort(403, 'Unauthorized access');
        }

        return view('accountant.reports');
    }

    /**
     * Display billing management
     */
    public function billing()
    {
        $accountant = Auth::user();

        if (!$accountant || !$accountant->hasRole('Accountant')) {
            abort(403, 'Unauthorized access');
        }

        return view('accountant.billing');
    }

    /**
     * Calculate total revenue
     */
    private function calculateTotalRevenue()
    {
        // This would typically query a payments table
        // For now, we'll simulate with appointments
        return Appointment::where('status', 'completed')->count() * 5000; // Assuming 5000 FCFA per appointment
    }

    /**
     * Calculate monthly revenue
     */
    private function calculateMonthlyRevenue()
    {
        return Appointment::where('status', 'completed')
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->count() * 5000;
    }

    /**
     * Get pending payments
     */
    private function getPendingPayments()
    {
        return Appointment::where('status', 'confirmed')->count();
    }

    /**
     * Get completed payments
     */
    private function getCompletedPayments()
    {
        return Appointment::where('status', 'completed')->count();
    }

    /**
     * Get service statistics
     */
    private function getServiceStatistics()
    {
        return Service::withCount(['appointments' => function($query) {
            $query->where('status', 'completed');
        }])->get();
    }

    /**
     * Get recent transactions
     */
    private function getRecentTransactions()
    {
        return Appointment::with(['user', 'service'])
            ->where('status', 'completed')
            ->orderBy('appointment_date', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get payment methods breakdown
     */
    private function getPaymentMethodsBreakdown()
    {
        // This would typically query a payments table
        // For now, we'll return mock data
        return [
            'cash' => 60,
            'card' => 25,
            'mobile_money' => 15
        ];
    }
}