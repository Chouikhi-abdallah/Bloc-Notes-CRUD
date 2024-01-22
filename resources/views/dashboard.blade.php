<link rel="icon" href="https://i.pinimg.com/originals/8c/f3/26/8cf32645fb8e1cf86fbe9a089256f142.jpg" type="image/x-icon">      
<x-app-layout>
    <!-- ... existing header content ... -->
    
    <div class="flex">
        <!-- Left Sidebar -->
        <!-- ... existing sidebar content ... -->

        <!-- Main Content Area -->
        <div class="flex-1 p-6 text-gray-900 dark:text-gray-100 font-poppins">
            <!-- Content chosen from the sidebar will be displayed here -->
            <div id="chosenContent" class="text-2xl font-bold mb-6">
                {{ __("Make Something Great ") }}
            </div>

            <!-- Display Page Sections with Animation -->
            <div id="pageSections" class="mt-4 grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($pages as $page)
                <div class="relative bg-white p-4 rounded-md shadow-md hover:shadow-lg transition duration-300 transform hover:scale-105 cursor-pointer" data-card-id="{{ $page->id }}">
                    <button onclick="enlargeCard('{{ $page->id }}', '{{ $page->title }}', '{{ $page->content }}')" class="absolute top-2 right-2 bg-blue-500 text-white rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <h2 class="text-xl font-semibold mb-2 text-black">{{ $page->title }}</h2>
                    <p class="text-gray-800">{{ $page->content }}</p>
                    <hr class="my-4">
                    <form method="post" action="{{ route('pages.destroy', ['page' => $page]) }}" onsubmit="return confirm('Are you sure you want to delete this page?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white rounded-md py-2 px-4 inline-block mt-2">Delete</button>
                    </form>
                </div>
                @endforeach
            </div>

            <!-- New Page Section Form -->
            <form method="post" action="{{ route('pages.store') }}" class="mt-6 animate__animated animate__fadeInUp">
                @csrf

                <div class="bg-white p-4 rounded-md shadow-md">
                    <label for="pageTitle" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" name="pageTitle" id="pageTitle" class="border rounded w-full py-2 px-3 mb-4 text-gray-800"
                           placeholder="Title..." required>

                    <label for="pageSection" class="block text-sm font-medium text-gray-700">Content:</label>
                    <textarea name="pageSection" id="pageSection" class="border rounded w-full py-2 px-3 mb-4 text-gray-800"
                              placeholder="Write something..." required></textarea>

                    <button type="submit" class="bg-green-500 text-white rounded-md py-2 px-4 inline-block">
                        Save Section
                    </button>
                </div>
            </form>

            <!-- Loading Message -->
            <div id="loadingMessage" class="hidden mt-4 text-gray-700 font-semibold">
                Deleting, please wait...
            </div>

            <!-- Enlarged Card Modal -->
            <div id="enlargedCardModal" class="fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 hidden">
                <div class="flex justify-center items-center h-full">
                    <div id="enlargedCardContent" class="bg-white p-6 rounded-md shadow-md text-gray-900 font-poppins">
                        <button onclick="closeEnlargedCard()" class="absolute top-2 right-2 text-gray-700 hover:text-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <h2 id="enlargedCardTitle" class="text-2xl font-semibold mb-4"></h2>
                        <p id="enlargedCardContentText" class="text-gray-800 mb-4"></p>
                        <textarea id="enlargedCardContentEditable" class="border rounded w-full py-2 px-3 mb-4 text-gray-800"
                                  placeholder="Write something..." required></textarea>
                        <button onclick="saveEnlargedCard()" class="bg-green-500 text-white rounded-md py-2 px-4 inline-block">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let editingCardId = null;

        function enlargeCard(cardId, cardTitle, cardContent) {
            // Update the modal content with fetched details
            let title = document.getElementById('enlargedCardTitle');
            let content = document.getElementById('enlargedCardContentText');
            let editableContent = document.getElementById('enlargedCardContentEditable');

            // Use the data passed to the function
            title.textContent = cardTitle;
            content.textContent = cardContent;
            editableContent.value = cardContent;

            // Show the enlarged card modal
            document.getElementById('enlargedCardModal').classList.remove('hidden');
            editingCardId = cardId; // Set the editingCardId for potential future updates
        }

        function closeEnlargedCard() {
            // Close the enlarged card modal
            document.getElementById('enlargedCardModal').classList.add('hidden');
        }

        function saveEnlargedCard() {
    // Fetch the updated content from the editable textarea
    let newContent = document.getElementById('enlargedCardContentEditable').value;

    // Save the updated content to the server based on the cardId using AJAX
    // Use an appropriate route like '/pages/' + editingCardId + '/update'
    // Update the content on the server and dynamically update the content on the page and in the modal

    // Example: Use fetch API for simplicity, you may use axios or other libraries
    fetch(`/pages/${editingCardId}/update`, {
        method: 'POST', // Use POST method for updating content
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            updatedContent: newContent
        })
    })
    .then(response => response.json())
    .then(data => {
        // Handle success (if needed)
        console.log('Content updated successfully:', data);

        // Update the content in the modal
        let modalContent = document.getElementById('enlargedCardContentText');
        modalContent.textContent = newContent;

        // Dynamically update the content on the corresponding card in the page
        let cardContent = document.querySelector(`#pageSections [data-card-id="${editingCardId}"] .text-gray-800`);
        if (cardContent) {
            cardContent.textContent = newContent;
        }

        // Close the enlarged card modal
        closeEnlargedCard();
    })
    .catch(error => {
        // Handle errors (if needed)
        console.error('Error updating content:', error);
    });
}
    </script>
</x-app-layout>
