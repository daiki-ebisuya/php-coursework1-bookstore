<?php
// Q1. books associated array
$books = [
    [
        'title' => 'Dune',
        'author' => 'Frank Herbert',
        'genre' => 'Science Fiction',
        'price' => 29.99
    ],
    [
        'title' => 'Harry Poter',
        'author' => 'J.K. Rowling',
        'genre' => 'Fantasy',
        'price' => 25.99
    ],
    [
        'title' => 'Sample Book',
        'author' => 'John Doe',
        'genre' => 'Documentary',
        'price' => 20.00
    ]
];



// 2. Discount Logic (pass-by-reference)
function applyDiscount(array &$books) {
    foreach ($books as &$book) {

        
        $book['original_price'] = $book['price'];// Save original price for display

        if ($book['genre'] === "Science Fiction") {

            
            $book['price'] = $book['price'] * 0.9;// 10% OFF science

        } elseif ($book['genre'] === "Fantasy") {

            
            $book['price'] = $book['price'] * 0.95;// 5% OFF fantasy

        } else {
            // No discount
            $book['price'] = $book['price'];
        }
    }
}


applyDiscount($books);



// 3. User Input Handling (POST + Validation)
if ($_SERVER["REQUEST_METHOD"] === "POST") {


    //setup initiatial data and if nodata in each key　.and trim space and br
    $title  = trim($_POST["title"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $genre  = trim($_POST["genre"] ?? "");
    $price  = $_POST["price"] ?? "";

    $errors = [];

    if ($title === "") $errors[] = "Title is required.";
    if ($author === "") $errors[] = "Author is required.";
    if ($genre === "") $errors[] = "Genre is required.";
    if ($price === "" || !is_numeric($price)) $errors[] = "Price must be numeric.";

    if (empty($errors)) {

        // add new books
        $books[] = [
            "title" => $title,
            "author" => $author,
            "genre" => $genre,
            "price" => (float)$price //cent is available by float
        ];

        // apply discount for new books (also saved regular price )
        applyDiscount($books);

        //Q7 added book information writing in log-text-file everytime
       $logMessage = "[" . date("Y-m-d H:i:s") . "] "
        . "IP: " . $_SERVER['REMOTE_ADDR'] . " | "
        . "UA: " . $_SERVER['HTTP_USER_AGENT'] . " | "
        . 'Added book: "' . $title . '" (' . $genre . ', ' . $price . ")\n";

        file_put_contents("bookstore_log.txt", $logMessage, FILE_APPEND);

        }
}



// Q4. Total Price 

$totalPrice = 0;
foreach ($books as $book) {
    $totalPrice += $book['price'];//merge total price
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Online Bookstore</title>
</head>
<body>

<h1>Online Bookstore</h1>

<!-- Q3. POST Form -->
<h2>Add a New Book</h2>
<form method="POST">
    <label>Title: <input type="text" name="title"></label><br><br>
    <label>Author: <input type="text" name="author"></label><br><br>
    <label>Genre: <input type="text" name="genre"></label><br><br>
    <label>Price: <input type="number" step="0.01" name="price"></label><br><br>
    <button type="submit">Add Book</button>
</form>

<?php if (!empty($errors)): ?>
    <p style="color:red;">
        <?php foreach($errors as $e) echo $e . "<br>"; ?>
    </p>
<?php endif; ?>

<!-- Q5. html table -->
<h2>Book List</h2>

<table border ="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Genre</th>
        <th>Original Price</th>
        <th>Discounted Price</th>
    </tr>

    <?php foreach ($books as $book): ?><!-- display each book dinamically-->
        <tr>
            <td><?= htmlspecialchars($book['title']) ?></td>
            <td><?= htmlspecialchars($book['author']) ?></td>
            <td><?= htmlspecialchars($book['genre']) ?></td>
            <td>$<?= number_format($book['original_price'], 2) ?></td> 
            <td>$<?= number_format($book['price'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Q4. Total Price display -->
<h3>Total Price After Discounts: $<?= number_format($totalPrice, 2) ?></h3>


<!-- Q6. Server Info(ip add) & Timestamp -->
<h3>Server Information</h3>
<p>Request Time: <?= date("Y-m-d H:i:s") ?></p>
<p>IP Address: <?= $_SERVER['REMOTE_ADDR'] ?></p>
<p>User Agent: <?= htmlspecialchars($_SERVER['HTTP_USER_AGENT']) ?></p>


    <!--display log-->
<h3>Log File Preview</h3>
<pre style="background:#f4f4f4; padding:10px; border:1px solid #ccc;">
<?= htmlspecialchars(file_get_contents("bookstore_log.txt")) ?>
</pre>

</body>
</html>






