<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to leave a review.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $existing = Review::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        ActivityLogService::log('review_submitted', 'Review submitted for product', auth()->user(), $review);

        return back()->with('success', 'Review submitted successfully!');
    }
}
