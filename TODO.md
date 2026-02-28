# Plan: Redesign Inventory Items Page

## Information Gathered:
- **Route**: `/inventory/items` - managed by `InventoryController@index`
- **Layout**: Uses `x-main-layout` which extends `layouts/layout.blade.php`
- **CSS Available**: Bootstrap 5, Font Awesome, custom CSS in layout
- **Functionality**: Search, Edit Modal, Delete Modal, Pagination

## Plan:
1. Remove all inline `<style>` blocks containing gradients
2. Redesign the page structure using Bootstrap 5:
   - Use `.container-fluid` with proper padding
   - Use `.row` and `.col-*` for layout
   - Clean card design without gradient headers
3. Improve typography:
   - Clear heading hierarchy
   - Proper font sizes and weights
4. Style the table:
   - Add `table-striped` for striped rows
   - Add `table-hover` for hover effects
   - Add responsive scrolling wrapper
5. Style buttons with flat colors (no gradients)
6. Add subtle shadows for depth
7. Ensure mobile responsiveness

## Files to Edit:
- `resources/views/custodian/inventory/items.blade.php` - Main redesign

## Follow-up Steps:
1. ✅ Test the page renders correctly
2. ✅ Verify all functionality still works (search, modals, pagination)
3. ✅ Test responsive behavior

## Status: COMPLETED ✅

