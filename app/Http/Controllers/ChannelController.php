<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Channel;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = Channel::orderBy('name', 'asc')->paginate(30);
        return view('channels.index', compact('channels'));
    }

    public function create()
    {
        return view('channels.create');
    }
    public function changeChannel(Request $request)
    {
        $channelId = $request->input('channel_id');
        $channel = Channel::findOrFail($channelId);

        // Stocker le channel_id dans la session
        Session::put('current_channel_id', $channelId);

        return response()->json(['logo' => Storage::url($channel->logo)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:channels',
            'description' => 'nullable|string',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        ]);

        $channel = new Channel();
        $channel->name = $validated['name'];
        $channel->description = $validated['description'];

        if ($request->hasFile('thumbnail_image')) {
            $thumbnailPath = $request->file('thumbnail_image')->store('public/channels');
            $channel->thumbnail_image = $thumbnailPath;
        }

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/channels');
            $channel->logo = $logoPath;
        }

        $channel->save();

        return redirect()->route('channels.index')->with('success', 'Channel ajouté avec succès.');
    }

    public function show($id)
    {
        $channel = Channel::findOrFail($id);
        return view('channels.show', compact('channel'));
    }

    public function edit($id)
    {
        $channel = Channel::findOrFail($id);
        return view('channels.edit', compact('channel'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:channels,name,' . $id,
            'description' => 'nullable|string',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        ]);

        $channel = Channel::findOrFail($id);
        $channel->name = $validated['name'];
        $channel->description = $validated['description'];

        if ($request->hasFile('thumbnail_image')) {
            $thumbnailPath = $request->file('thumbnail_image')->store('public/channels');
            $channel->thumbnail_image = $thumbnailPath;
        }

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/channels');
            $channel->logo = $logoPath;
        }

        $channel->save();

        return redirect()->route('channels.index')->with('success', 'Channel mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $channel = Channel::findOrFail($id);
        $channel->delete();

        return redirect()->route('channels.index')->with('success', 'Channel supprimé avec succès.');
    }
}
