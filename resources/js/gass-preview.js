const preview = document.querySelector('[data-gass-preview]');

if (preview) {
    const type = preview.dataset.previewType;
    const contentUrl = preview.dataset.contentUrl;
    const status = preview.querySelector('[data-preview-status]');
    const officePreview = preview.querySelector('[data-office-preview]');

    const setError = (error) => {
        if (status) {
            status.textContent = error;
            status.classList.add('error');
        }
    };

    const loadFile = async () => {
        const response = await fetch(contentUrl, { credentials: 'same-origin' });
        if (!response.ok) {
            throw new Error('File tidak dapat dimuat. Silakan muat ulang atau unduh file.');
        }
        return response.arrayBuffer();
    };

    const renderText = async () => {
        const bytes = await loadFile();
        const text = new TextDecoder().decode(bytes);
        const content = document.createElement('pre');
        content.className = 'gass-preview-text';
        content.textContent = text;
        officePreview.replaceChildren(content);
        status?.remove();
    };

    const renderWorkbook = async () => {
        const { default: ExcelJS } = await import('exceljs');
        const workbook = new ExcelJS.Workbook();
        await workbook.xlsx.load(await loadFile());
        if (!workbook.worksheets.length) {
            throw new Error('Workbook ini tidak berisi sheet yang dapat ditampilkan.');
        }

        const tabs = document.createElement('div');
        tabs.className = 'gass-sheet-tabs';
        const tableContainer = document.createElement('div');

        const showSheet = (worksheet) => {
            tabs.querySelectorAll('button').forEach((button) => {
                const active = button.dataset.sheetName === worksheet.name;
                button.classList.toggle('active', active);
                button.setAttribute('aria-pressed', String(active));
            });

            const table = document.createElement('table');
            table.className = 'gass-sheet-table';
            const body = document.createElement('tbody');
            const rowLimit = Math.min(worksheet.rowCount, 500);
            const columnLimit = Math.min(worksheet.columnCount, 100);

            for (let rowIndex = 1; rowIndex <= rowLimit; rowIndex += 1) {
                const tr = document.createElement('tr');
                for (let columnIndex = 1; columnIndex <= columnLimit; columnIndex += 1) {
                    const workbookCell = worksheet.getRow(rowIndex).getCell(columnIndex);
                    const tableCell = document.createElement(rowIndex === 1 ? 'th' : 'td');
                    tableCell.textContent = workbookCell.text || '';
                    tr.append(tableCell);
                }
                body.append(tr);
            }

            table.append(body);
            tableContainer.replaceChildren(table);

            if (worksheet.rowCount > rowLimit || worksheet.columnCount > columnLimit) {
                const note = document.createElement('p');
                note.className = 'gass-office-message';
                note.textContent = `Pratinjau dibatasi sampai ${rowLimit} baris dan ${columnLimit} kolom. Unduh file untuk melihat seluruh data.`;
                tableContainer.append(note);
            }
        };

        workbook.worksheets.forEach((worksheet) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'gass-sheet-tab';
            button.textContent = worksheet.name;
            button.dataset.sheetName = worksheet.name;
            button.addEventListener('click', () => showSheet(worksheet));
            tabs.append(button);
        });

        officePreview.replaceChildren(tabs, tableContainer);
        showSheet(workbook.worksheets[0]);
        status?.remove();
    };

    const renderWord = async () => {
        const [{ default: mammoth }, { default: DOMPurify }] = await Promise.all([
            import('mammoth/mammoth.browser'),
            import('dompurify'),
        ]);
        const result = await mammoth.convertToHtml({ arrayBuffer: await loadFile() });
        const article = document.createElement('article');
        article.className = 'gass-docx';
        article.innerHTML = DOMPurify.sanitize(result.value, {
            USE_PROFILES: { html: true },
            ALLOWED_URI_REGEXP: /^data:image\/(?:png|gif|jpe?g|webp);base64,/i,
        });
        officePreview.replaceChildren(article);
        status?.remove();
    };

    const renderers = { text: renderText, excel: renderWorkbook, word: renderWord };

    renderers[type]?.().catch((error) => {
        console.error('GASS preview could not be rendered.', error);
        setError(error instanceof Error ? error.message : 'Pratinjau file gagal dimuat.');
    });
}
