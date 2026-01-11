<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-foreground">Reported Events</h1>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
            @if($reports->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    No reports found.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Reported Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Reporter</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reports as $report)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($report->event)
                                            <a href="{{ route('events.show', $report->event) }}"
                                                class="text-blue-600 hover:text-blue-900 font-medium">
                                                {{ $report->event->title }}
                                            </a>
                                            <div class="text-xs text-gray-500">
                                                By: {{ $report->event->user->name ?? 'Unknown' }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Event Deleted</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->user->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $report->reason }}">
                                        {{ Str::limit($report->reason, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->created_at->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <!-- Actions -->
                                        @if($report->event)
                                            <a href="{{ route('events.edit', $report->event) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Edit Event</a>

                                            <form action="{{ route('events.destroy', $report->event) }}" method="POST"
                                                class="inline-block" onsubmit="return confirm('Delete this event permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 border-l pl-2 ml-2 border-gray-300">Delete
                                                    Event</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.reports.destroy', $report) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Dismiss this report?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-gray-500 hover:text-gray-700 border-l pl-2 ml-2 border-gray-300">Dismiss
                                                Report</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>