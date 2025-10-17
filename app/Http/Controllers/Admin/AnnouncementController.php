<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AnnouncementRequest;
use App\Models\AnnouncementCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = AnnouncementCard::query()
            ->latest('created_at')
            ->paginate(12);

        return view('admin.announcements.index', [
            'announcements' => $announcements,
        ]);
    }

    public function create(): View
    {
        return view('admin.announcements.create', [
            'announcement' => new AnnouncementCard(),
        ]);
    }

    public function store(AnnouncementRequest $request): RedirectResponse
    {
        AnnouncementCard::create($request->validated());

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'Alert created successfully.');
    }

    public function edit(AnnouncementCard $announcement): View
    {
        return view('admin.announcements.edit', [
            'announcement' => $announcement,
        ]);
    }

    public function update(AnnouncementRequest $request, AnnouncementCard $announcement): RedirectResponse
    {
        $announcement->update($request->validated());

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'Alert updated successfully.');
    }

    public function destroy(AnnouncementCard $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'Alert removed.');
    }
}
