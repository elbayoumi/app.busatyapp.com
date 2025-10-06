<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postman Collection Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Manage Postman Collections</h1>

        <!-- File Upload Form -->
        <div class="card mt-4">
            <div class="card-header">Upload Postman Collection</div>
            <div class="card-body">
                <form id="uploadForm">
                    <div class="mb-3">
                        <label for="postmanFile" class="form-label">Select Postman Collection JSON File</label>
                        <input type="file" id="postmanFile" name="postman_file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>

        <!-- Collections List -->
        <div class="card mt-4">
            <div class="card-header">Uploaded Collections</div>
            <div class="card-body">
                <ul id="collectionsList" class="list-group">
                    <!-- Collections will be dynamically populated here -->
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Fetch and display collections
        async function fetchCollections() {
            const response = await fetch('/collections');
            console.log(response);
            const collections = await response.json();
            const collectionsList = document.getElementById('collectionsList');
            collectionsList.innerHTML = '';

            collections.forEach(collection => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.textContent = collection.name;

                const viewButton = document.createElement('button');
                viewButton.className = 'btn btn-sm btn-info';
                viewButton.textContent = 'View APIs';
                viewButton.onclick = () => fetchApis(collection.id);

                li.appendChild(viewButton);
                collectionsList.appendChild(li);
            });
        }

        // Fetch and display APIs for a collection
        async function fetchApis(collectionId) {
            const response = await fetch(`/collections/${collectionId}/apis`);
            const apis = await response.json();
            alert(`APIs for Collection ID ${collectionId}:\\n` + apis.map(api => `${api.method} ${api.url}`).join('\\n'));
        }

        // Handle file upload
        document.getElementById('uploadForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            const response = await fetch('/collections/import', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                alert('Collection uploaded successfully!');
                fetchCollections();
            } else {
                alert('Failed to upload collection.');
            }
        });

        // Initial fetch of collections
        fetchCollections();
    </script>
</body>
</html>
