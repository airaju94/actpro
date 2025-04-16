/**
 * Advanced Table tableSocter Class with Auto-Detection
 * Automatically detects data types and enables sorting functionality
 * 
 * Features:
 * - Auto-detects data types (string, numeric, date, boolean)
 * - No need for data attributes
 * - Toggle between ascending/descending sort
 * - Custom sort indicators
 * - Multiple table support
 * - Lightweight and efficient
 * 
 * Usage:
 * Simply initialize with: new tableSocter('#tableId') or new tableSocter('.tableClass')
 */
class tableSocter {
    constructor(selector) {
        this.tables = document.querySelectorAll(selector);
        
        if (this.tables.length === 0) {
            console.warn(`tableSocter: No tables found with selector "${selector}"`);
            return;
        }
        
        this.initTables();
    }
    
    initTables() {
        this.tables.forEach(table => {
            const headers = table.querySelectorAll('th');
            
            headers.forEach(header => {
                // Create arrow element
                const arrow = document.createElement('span');
                arrow.className = 'sort-arrow';
                arrow.setAttribute('style', 'margin-left: 10px;font-weight:bold;color:var(--bs-primary);font-size:innerit;');
                header.appendChild(arrow);
                
                header.addEventListener('click', () => this.sort(table, header));
            });
            
            this.analyzeTableTypes(table);
        });
    }
    
    analyzeTableTypes(table) {
        const headers = table.querySelectorAll('th');
        const rows = table.querySelectorAll('tbody tr');
        
        if (rows.length === 0) return;
        
        headers.forEach((header, colIndex) => {
            const sampleCells = Array.from(rows).slice(0, 5).map(row => row.cells[colIndex]);
            const detectedType = this.detectColumnType(sampleCells);
            
            if (detectedType === 'numeric') {
                table.querySelectorAll(`tbody tr td:nth-child(${colIndex + 1})`)
                    .forEach(cell => cell.classList.add('numeric'));
            }
        });
    }
    
    detectColumnType(sampleCells) {
        let numericCount = 0;
        let dateCount = 0;
        let booleanCount = 0;
        const totalSamples = sampleCells.length;
        sampleCells.forEach(cell => {
            if( cell !== undefined ){
                const content = cell.textContent.trim();
                if (/^-?\d+\.?\d*$/.test(content)) {
                    numericCount++;
                }
                else if (this.isValidDate(content)) {
                    dateCount++;
                }
                else if (/^(yes|no|true|false)$/i.test(content)) {
                    booleanCount++;
                }
            }
        });
        
        if (numericCount / totalSamples > 0.7) return 'numeric';
        if (dateCount / totalSamples > 0.7) return 'date';
        if (booleanCount / totalSamples > 0.7) return 'boolean';
        return 'string';
    }
    
    isValidDate(dateString) {
        const datePatterns = [
            /^\d{4}-\d{2}-\d{2}$/,
            /^\d{2}\/\d{2}\/\d{4}$/
        ];
        
        return datePatterns.some(pattern => pattern.test(dateString)) && 
               !isNaN(new Date(dateString).getTime());
    }
    
    sort(table, header) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const colIndex = Array.from(header.parentNode.children).indexOf(header);
        const sampleCells = rows.slice(0, 5).map(row => row.cells[colIndex]);
        const dataType = this.detectColumnType(sampleCells);
        const currentArrow = header.querySelector('.sort-arrow');
        let direction;
        
        // Determine new direction
        if (currentArrow.textContent === '⤊') {
            direction = 'desc';
            currentArrow.innerHTML = '⤋';
        } else if (currentArrow.textContent === '⤋') {
            direction = 'asc';
            currentArrow.innerHTML = '⤊';
        } else {
            direction = 'asc';
            currentArrow.innerHTML = '⤊';
        }
        
        // Clear arrows from other headers
        table.querySelectorAll('.sort-arrow').forEach(arrow => {
            if (arrow !== currentArrow) arrow.textContent = '';
        });
        
        // Sort rows
        rows.sort((a, b) => {
            const aCell = a.cells[colIndex];
            const bCell = b.cells[colIndex];
            let aValue = this.getSortValue(aCell, dataType);
            let bValue = this.getSortValue(bCell, dataType);
            
            if (aValue < bValue) return direction === 'asc' ? -1 : 1;
            if (aValue > bValue) return direction === 'asc' ? 1 : -1;
            return 0;
        });
        
        // Rebuild table
        rows.forEach(row => tbody.appendChild(row));
    }
    
    getSortValue(cell, dataType) {
        const content = cell.textContent.trim().toLowerCase();
        
        switch (dataType) {
            case 'numeric':
                return parseFloat(content) || 0;
            case 'date':
                return new Date(content);
            case 'boolean':
                return content === 'yes' || content === 'true';
            default:
                return content;
        }
    }
}

// // Initialize the sorter
// document.addEventListener('DOMContentLoaded', () => {
//     new tableSocter('#users');
//     new tableSocter('.sortable');
// });