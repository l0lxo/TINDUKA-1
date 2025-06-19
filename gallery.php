<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery</title>
    <style>
        /* Your existing CSS styles remain the same */
    </style>
</head>
<body style="margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: white; color: #333;">
<?php
session_start();
include('config.php');
include 'includes/navbar.php';

// Fetch photos from database with user information
$sql = "SELECT photos.*, users.firstname, users.lastname, users.profile_picture 
        FROM photos 
        JOIN users ON photos.user_id = users.user_id 
        ORDER BY created_at DESC";
$result = $conn->query($sql);

$photos = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Convert stored path to web-accessible URL
        $row['photo_url'] = getFullUrl($row['photo_url']);
        $photos[] = $row;
    }
}

function getFullUrl($path) {
    // Remove any accidental duplicate 'uploads' in path
    $path = str_replace('uploads/uploads/', 'uploads/', $path);
    
    // If path is already a full URL, return as-is
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        return $path;
    }
    
    // Convert relative path to full URL
    $base_url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
    return rtrim($base_url, '/') . '/' . ltrim($path, '/');
}
?>
    
    <!-- Header Section -->
    <div style="padding: 20px; background-color: #4CAF50; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="color: white; margin: 0 0 15px; font-size: 2rem; font-weight: 700;">Photo Gallery</h2>
        <div style="display: flex; justify-content: center; align-items: center; gap: 10px; flex-wrap: wrap;">
            <select id="locationFilter" style="padding: 12px; font-size: 16px; border: none; border-radius: 8px; background-color: white; color: #333; cursor: pointer; transition: background-color 0.2s;">
                <option value="all">All Locations</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
            <select id="countyFilter" style="padding: 12px; font-size: 16px; border: none; border-radius: 8px; background-color: white; color: #333; cursor: pointer; transition: background-color 0.2s;">
                <option value="all">All Counties</option>
                <option value="Nairobi">Nairobi</option>
                <option value="Mombasa">Mombasa</option>
                <option value="Kisumu">Kisumu</option>
                <option value="Kisi">Kisi</option>
            </select>
            <a href="submit_photo.php" style="padding: 12px 20px; background-color: white; color: #4CAF50; text-decoration: none; border-radius: 8px; font-weight: 500; transition: background-color 0.2s, color 0.2s;">Submit a New Photo</a>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div id="gallery" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; padding: 20px; background-color: #f9f9f9;">
    <?php foreach ($photos as $photo): ?>
        <div class="photo-card" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <img src="<?php echo htmlspecialchars($photo['photo_url']); ?>" 
                 alt="<?php echo htmlspecialchars($photo['caption']); ?>" 
                 style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                 onclick="openModal('<?php echo htmlspecialchars($photo['photo_url']); ?>', '<?php echo htmlspecialchars($photo['caption']); ?>')">
            <div style="padding: 10px;">
                <p style="margin: 5px 0; font-weight: bold;"><?php echo htmlspecialchars($photo['caption']); ?></p>
                <small style="color: #666;"><?php echo htmlspecialchars($photo['county'] . ', ' . $photo['location']); ?></small>
            </div>
        </div>
    <?php endforeach; ?>
</div>

    <!-- Modal -->
    <div id="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.85); justify-content: center; align-items: center; z-index: 1000;">
        <span id="closeModal" style="position: absolute; top: 20px; right: 20px; color: white; font-size: 36px; font-weight: bold; cursor: pointer; transition: color 0.2s;">×</span>
        <div style="text-align: center; max-width: 90%; max-height: 90%;">
            <img id="modalImage" style="max-width: 100%; max-height: 60vh; border: 5px solid #4CAF50; border-radius: 8px; object-fit: contain;">
            <div style="margin-top: 15px; color: white;">
                <p id="modalCaption" style="font-size: 1.2rem; font-weight: 500; margin: 10px 0;"></p>
                <p id="modalCounty" style="font-size: 1rem; margin: 5px 0;">County: <span></span></p>
                <p id="modalLocation" style="font-size: 1rem; margin: 5px 0;">Location: <span></span></p>
            </div>
        </div>
        <button id="prevBtn" style="position: absolute; left: 15px; background-color: #4CAF50; color: white; border: none; padding: 12px; font-size: 24px; border-radius: 8px; cursor: pointer; transition: background-color 0.2s;">❮</button>
        <button id="nextBtn" style="position: absolute; right: 15px; background-color: #4CAF50; color: white; border: none; padding: 12px; font-size: 24px; border-radius: 8px; cursor: pointer; transition: background-color 0.2s;">❯</button>
    </div>
    <?php include 'includes/footer.html'; ?>
    
    <script>
        // Convert PHP photos array to JavaScript
        const photos = <?php echo json_encode($photos); ?>;
        const gallery = document.getElementById("gallery");
        const locationFilter = document.getElementById("locationFilter");
        const countyFilter = document.getElementById("countyFilter");
        const modal = document.getElementById("modal");
        const modalImage = document.getElementById("modalImage");
        const modalCaption = document.getElementById("modalCaption");
        const modalCounty = document.getElementById("modalCounty").getElementsByTagName("span")[0];
        const modalLocation = document.getElementById("modalLocation").getElementsByTagName("span")[0];
        const closeModal = document.getElementById("closeModal");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");
        let currentPhotoIndex = 0;
        let filteredPhotos = photos;

        function populateGallery(photos) {
            gallery.innerHTML = "";
            photos.forEach((photo, index) => {
                const img = document.createElement("img");
                img.src = photo.photo_url;
                img.alt = photo.caption || "Photo";
                img.style.width = "100%";
                img.style.height = "200px";
                img.style.objectFit = "cover";
                img.style.borderRadius = "8px";
                img.style.cursor = "pointer";
                img.style.transition = "transform 0.2s";
                img.onmouseover = () => img.style.transform = "scale(1.05)";
                img.onmouseout = () => img.style.transform = "scale(1)";
                img.onclick = () => openModal(index);
                
                gallery.appendChild(img);
            });
        }

        function filterPhotos() {
            const location = locationFilter.value;
            const county = countyFilter.value;
            filteredPhotos = photos.filter(photo => 
                (location === "all" || photo.location === location) &&
                (county === "all" || photo.county === county)
            );
            populateGallery(filteredPhotos);
        }

        function openModal(index) {
            currentPhotoIndex = index;
            const photo = filteredPhotos[index];
            modalImage.src = photo.photo_url;
            modalCaption.textContent = photo.caption || "No caption";
            modalCounty.textContent = photo.county || "Unknown";
            modalLocation.textContent = photo.location || "Unknown";
            modal.style.display = "flex";
        }

        closeModal.onclick = () => modal.style.display = "none";
        prevBtn.onclick = () => {
            currentPhotoIndex = (currentPhotoIndex - 1 + filteredPhotos.length) % filteredPhotos.length;
            openModal(currentPhotoIndex);
        };
        nextBtn.onclick = () => {
            currentPhotoIndex = (currentPhotoIndex + 1) % filteredPhotos.length;
            openModal(currentPhotoIndex);
        };

        locationFilter.onchange = filterPhotos;
        countyFilter.onchange = filterPhotos;

        // Initialize gallery
        populateGallery(photos);
    </script>
</body>
</html>