<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postman Data Viewer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .main-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .sub-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        h1 {
            color: #333;
        }
        pre {
            background-color: #f4f4f4;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow-x: auto;
        }
        .json-key {
            color: #007bff;
        }
        .json-value-string {
            color: #d9534f;
        }
        .json-value-number {
            color: #5cb85c;
        }
        .json-value-boolean {
            color: #f0ad4e;
        }
    </style>
</head>
<body>
    <div class="main-section">
        <h1>Postman File Content</h1>
        <div class="sub-section">
            <h2>JSON Data</h2>
            <div id="json-container">Loading data...</div>
        </div>
    </div>

    <script>
        // Fetch JSON data
        const url = '{{ asset("storage/api/api.json") }}'; // رابط الملف

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const container = document.getElementById('json-container');
                container.innerHTML = '<pre>' + syntaxHighlight(JSON.stringify(data, null, 2)) + '</pre>';
            })
            .catch(error => {
                document.getElementById('json-container').textContent = `Error: ${error.message}`;
            });

        // Syntax highlight JSON
        function syntaxHighlight(json) {
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+-]?\d+)?)/g, function (match) {
                let cls = 'json-value-number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'json-key';
                    } else {
                        cls = 'json-value-string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'json-value-boolean';
                } else if (/null/.test(match)) {
                    cls = 'json-value-null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }
    </script>
</body>
</html>
