@auth
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Leave a Review</h3>
        <form action="{{ route('reviews.store', $advertisement->id) }}" method="POST" class="space-y-4 mt-4">
            @csrf
            <div>
                <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rating</label>
                <select name="rating" id="rating" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700">
                    <option value="5">5 ⭐</option>
                    <option value="4">4 ⭐</option>
                    <option value="3">3 ⭐</option>
                    <option value="2">2 ⭐</option>
                    <option value="1">1 ⭐</option>
                </select>
            </div>
            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comment</label>
                <textarea name="comment" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700"></textarea>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Submit Review</button>
        </form>
    </div>
@endauth
