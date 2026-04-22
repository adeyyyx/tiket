<?php if($is_dashboard_layout): ?>
            </div> <!-- end container konten bg-light -->
        </div> <!-- end flex-grow-1 (konten kanan) -->
    </div> <!-- end d-flex (layout dashboard) -->
<?php else: ?>
    </div> <!-- end container -->
    <footer class="bg-white border-top py-4 mt-auto">
        <div class="container text-center">
            <p class="text-muted mb-0">&copy; <?= date('Y') ?> TicketApp. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
