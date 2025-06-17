<!-- resources/views/projects/create.php -->
<?php /** @var \App\Models\Space $space */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Project in "<?php echo htmlspecialchars($space->name); ?>"</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto py-10">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 mb-6">
            Create Project in "<?php echo htmlspecialchars($space->name); ?>"
        </h2>
        <form method="POST" action="<?php echo route('spaces.projects.store', $space->id); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Project</button>
            </div>
        </form>
    </div>
</body>
</html>
