<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Submission</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', sans-serif;
        }
        body {
            background-color: #f9f9f9;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .form-container {
            background-color: #333;
            padding: 15px;
            max-width: 90%;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.5);
            color: white;
        }
        h1 {
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
            color: white;
            text-align: center;
        }
        form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: white;
        }
        form input[type="text"],
        form textarea,
        form input[type="text"],
        form input[type="file"],
        select {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.25rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #444;
            color: white;
            transition: border-color 0.2s ease;
        }
        form input[type="text"]:focus,
        form textarea:focus,
        form input[type="file"]:focus,
        select:focus {
            border-color: #4CAF50;
            outline: none;
            background-color: #555;
        }
        form button {
            width: 100%;
            padding: 0.75rem;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        form button:hover {
            background-color: #45a049;
        }
        .nav-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #4CAF50;
            text-decoration: none;
            font-size: 1rem;
        }
        .nav-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .form-container {
                padding: 1.5rem;
            }
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <?php include 'includes/navbar.php'; ?>
        <h1>Submit a Photo</h1>
        <form id="photoForm">
            <label for="photo">Upload Image</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>

            <label for="title">Photo Caption</label>
            <input type="text" id="title" name="title" placeholder="Write a captivating caption..." required>

            <label for="county">County</label>
            <select id="county" name="county" required>
                <option value="" disabled selected>Select a county</option>
                <option value="Nairobi">Nairobi</option>
                <option value="Mombasa">Mombasa</option>
                <option value="Kisumu">Kisumu</option>
                <option value="Kisi">Kisi</option>
            </select>

            <label for="place">Location</label>
            <select id="place" name="place" required>
                <option value="" disabled selected>Select a location</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>

            <button type="submit">Submit</button>
        </form>
        <a href="gallery.php" class="nav-link">View Gallery</a>
    </div>
        <?php include 'includes/footer.php'; ?>
    <script>
        document.getElementById("photoForm").onsubmit = function(e) {
            e.preventDefault();
            const photo = document.getElementById("photo").files[0];
            const title = document.getElementById("title").value;
            const county = document.getElementById("county").value;
            const place = document.getElementById("place").value;

            const reader = new FileReader();
            reader.onload = function() {
                const photos = JSON.parse(localStorage.getItem("photos") || "[]");
                photos.push({
                    src: reader.result,
                    caption: title,
                    county: county,
                    location: place
                });
                localStorage.setItem("photos", JSON.stringify(photos));
                alert("Photo submitted successfully!");
                window.location.href = "gallery.html"; // Redirect to gallery
            };
            reader.readAsDataURL(photo);
        };
    </script>
</body>
</html>