<?php 
// Set page title
$pageTitle = "Test Page";

// Include meta and header
include 'components/meta.php';
include 'components/header.php';
?>

<!-- Main Content -->
<div class="container section-padding">
    <div class="row">
        <div class="col-12 text-center">
            <h1 class="section-title">Welcome to Ilyass Fit</h1>
            <p class="section-subtitle">This is a test page to verify header and footer components</p>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-md-6">
            <h3>Test Section 1</h3>
            <p>This is some sample content to test the layout. The header should appear at the top with the navigation menu, and the footer should appear at the bottom with contact information and map.</p>
            <button class="btn btn-primary mt-3">Primary Button</button>
        </div>
        <div class="col-md-6">
            <h3>Test Section 2</h3>
            <p>Check that all fonts are loading correctly (Inter for body text, Montserrat for headings). Also verify that the brand colors (#fc0404, #1b1f22, #212529) are displaying properly.</p>
            <button class="btn btn-outline-primary mt-3">Outline Button</button>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <div class="bg-light-gray p-4" style="border-radius: 10px;">
                <h3 class="text-red">Features to Check:</h3>
                <ul style="color: var(--text-gray);">
                    <li>✓ Header with logo and navigation links</li>
                    <li>✓ Active link highlighting</li>
                    <li>✓ Responsive navbar (test on mobile)</li>
                    <li>✓ Footer with contact info and map</li>
                    <li>✓ Social media icons</li>
                    <li>✓ Brand colors and fonts</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php 
// Include footer
include 'components/footer.php';
?>
