(function () {
    'use strict';

    const $ = (selector, scope = document) => scope.querySelector(selector);
    const $$ = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));
    const csrf = () => $('meta[name="csrf-token"]')?.content || '';

    function setLiveMessage(message) {
        let node = $('[data-live-message]');
        if (!node) {
            node = document.createElement('div');
            node.className = 'visually-hidden';
            node.setAttribute('role', 'status');
            node.setAttribute('aria-live', 'polite');
            node.setAttribute('data-live-message', '');
            document.body.appendChild(node);
        }
        node.textContent = message;
    }

    /* ---------------------------------- Navigation --------------------------------- */
    const navToggle = $('[data-nav-toggle]');
    const mobileNav = $('#mobile-nav');

    if (navToggle && mobileNav) {
        navToggle.addEventListener('click', () => {
            const isOpen = navToggle.getAttribute('aria-expanded') === 'true';
            navToggle.setAttribute('aria-expanded', String(!isOpen));
            navToggle.setAttribute('aria-label', isOpen ? 'Open menu' : 'Close menu');
            mobileNav.hidden = isOpen;
        });
    }

    // Details menus close when attention moves elsewhere.
    document.addEventListener('click', (event) => {
        $$('details.user-menu[open]').forEach((details) => {
            if (!details.contains(event.target)) {
                details.open = false;
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        $$('details.user-menu[open]').forEach((details) => { details.open = false; });
    });

    /* ------------------------------------ Copy ------------------------------------- */
    $$('[data-copy-target]').forEach((button) => {
        button.addEventListener('click', async () => {
            const target = $(button.dataset.copyTarget);
            if (!target) return;
            const value = (target.textContent || target.value || '').trim();
            try {
                await navigator.clipboard.writeText(value);
                setLiveMessage('Copied to the clipboard.');
                button.setAttribute('title', 'Copied');
            } catch (error) {
                setLiveMessage('Could not copy. Select the link and copy it manually.');
            }
        });
    });

    /* ----------------------------------- Dialogs ----------------------------------- */
    $$('[data-dialog-open]').forEach((button) => {
        button.addEventListener('click', () => {
            const dialog = document.getElementById(button.dataset.dialogOpen);
            if (dialog && typeof dialog.showModal === 'function') {
                dialog.showModal();
                const focusable = dialog.querySelector('input, button, select, textarea, a[href]');
                if (focusable) window.setTimeout(() => focusable.focus(), 50);
            }
        });
    });

    $$('dialog').forEach((dialog) => {
        $$('[data-dialog-close]', dialog).forEach((button) => {
            button.addEventListener('click', () => dialog.close());
        });
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) dialog.close();
        });
    });

    /* ---------------------------------- Repeaters ---------------------------------- */
    function renumber(list) {
        $$('[data-item-number]', list).forEach((node, index) => {
            node.textContent = String(index + 1).padStart(2, '0');
        });
    }

    function updateProjectCount(form) {
        const list = $('[data-project-list]', form);
        if (!list) return;
        const count = $$('[data-project-item]', list).length;
        const label = $('[data-project-count]', form);
        if (label) label.textContent = String(count);
        const add = $('[data-add-project]', form);
        if (add) {
            add.disabled = count >= 12;
            add.setAttribute('aria-disabled', String(count >= 12));
        }
    }

    function nudgeForm(form) {
        if (!form) return;
        form.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function addRepeaterItem(form, button) {
        const templateId = button.dataset.addProject !== undefined ? 'project-template' : 'link-template';
        const itemSelector = button.dataset.addProject !== undefined ? '[data-project-item]' : '[data-link-item]';
        const listSelector = button.dataset.addProject !== undefined ? '[data-project-list]' : '[data-link-list]';
        const template = document.getElementById(templateId);
        const list = $(listSelector, form);
        if (!template || !list) return;
        if (button.dataset.addProject !== undefined && $$(itemSelector, list).length >= 12) return;

        const index = String(Date.now()) + String(Math.floor(Math.random() * 1000));
        const number = $$(itemSelector, list).length + 1;
        const fragment = template.innerHTML
            .replace(/__INDEX__/g, index)
            .replace(/__NUMBER__/g, String(number).padStart(2, '0'));
        const holder = document.createElement('div');
        holder.innerHTML = fragment.trim();
        const item = holder.firstElementChild;
        if (!item) return;

        list.appendChild(item);
        renumber(list);
        if (button.dataset.addProject !== undefined) updateProjectCount(form);
        const firstInput = $('input, textarea', item);
        if (firstInput) firstInput.focus();
        nudgeForm(form);
    }

    document.addEventListener('click', (event) => {
        const addProject = event.target.closest('[data-add-project]');
        const addLink = event.target.closest('[data-add-link]');
        if (addProject) {
            event.preventDefault();
            addRepeaterItem(addProject.closest('form'), addProject);
            return;
        }
        if (addLink) {
            event.preventDefault();
            addRepeaterItem(addLink.closest('form'), addLink);
            return;
        }

        const remove = event.target.closest('[data-remove-item]');
        if (remove) {
            event.preventDefault();
            const item = remove.closest('[data-project-item], [data-link-item]');
            const form = remove.closest('form');
            if (!item) return;
            const list = item.parentElement;
            item.remove();
            renumber(list);
            updateProjectCount(form);
            nudgeForm(form);
            return;
        }

        const move = event.target.closest('[data-move]');
        if (move) {
            event.preventDefault();
            const item = move.closest('[data-project-item], [data-link-item]');
            if (!item) return;
            if (move.dataset.move === 'up' && item.previousElementSibling) {
                item.parentElement.insertBefore(item, item.previousElementSibling);
            }
            if (move.dataset.move === 'down' && item.nextElementSibling) {
                item.parentElement.insertBefore(item.nextElementSibling, item);
            }
            renumber(item.parentElement);
            nudgeForm(item.closest('form'));
        }
    });

    document.addEventListener('input', (event) => {
        const titleInput = event.target.closest('[data-repeat-title-input]');
        if (!titleInput) return;
        const item = titleInput.closest('[data-project-item], [data-link-item]');
        const title = $('[data-repeat-title]', item);
        if (title) {
            title.textContent = titleInput.value.trim() || (item.hasAttribute('data-project-item') ? 'Untitled project' : 'Untitled link');
        }
    });

    /* --------------------------------- Image uploads -------------------------------- */
    function setUploadNote(field, message, isError = false) {
        const note = $('[data-upload-note]', field);
        if (!note) return;
        note.textContent = message;
        note.style.color = isError ? 'var(--danger)' : '';
    }

    document.addEventListener('change', (event) => {
        const input = event.target.closest('[data-image-upload]');
        if (!input || !input.files || !input.files[0]) return;
        const field = input.closest('[data-image-field]');
        const form = input.closest('form');
        const hidden = $('[data-image-input]', field);
        const thumb = $('[data-image-thumb]', field);
        const uploadUrl = form?.dataset.uploadUrl;
        if (!field || !hidden || !thumb || !uploadUrl) return;

        const file = input.files[0];
        if (file.size > 5 * 1024 * 1024) {
            setUploadNote(field, 'That file is over 5 MB.', true);
            input.value = '';
            return;
        }

        const button = input.closest('.upload-btn');
        const original = button ? button.innerHTML : '';
        if (button) {
            button.style.pointerEvents = 'none';
            button.style.opacity = '0.6';
        }
        setUploadNote(field, 'Uploading and re-encoding…');

        const data = new FormData();
        data.append('file', file);
        data.append('kind', 'project');

        fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf(),
            },
            body: data,
            credentials: 'same-origin',
        })
            .then(async (response) => {
                const payload = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(payload.error || (payload.errors && Object.values(payload.errors).flat()[0]) || 'Upload failed.');
                return payload;
            })
            .then((payload) => {
                hidden.value = payload.path;
                const image = document.createElement('img');
                image.src = payload.url;
                image.alt = '';
                image.loading = 'lazy';
                thumb.replaceChildren(image);
                setUploadNote(field, 'Uploaded. Save the page to keep it.');
                hidden.dispatchEvent(new Event('change', { bubbles: true }));
            })
            .catch((error) => {
                setUploadNote(field, error.message || 'Upload failed. Try a smaller image.', true);
            })
            .finally(() => {
                input.value = '';
                if (button) {
                    button.innerHTML = original;
                    button.style.pointerEvents = '';
                    button.style.opacity = '';
                }
            });
    });

    document.addEventListener('click', (event) => {
        const clear = event.target.closest('[data-image-clear]');
        if (!clear) return;
        event.preventDefault();
        const field = clear.closest('[data-image-field]');
        if (!field) return;
        const hidden = $('[data-image-input]', field);
        const thumb = $('[data-image-thumb]', field);
        if (hidden) {
            hidden.value = '';
            hidden.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (thumb) {
            const empty = document.createElement('span');
            empty.textContent = 'No image';
            empty.style.fontSize = '9px';
            thumb.replaceChildren(empty);
        }
        setUploadNote(field, 'Image removed. Save the page.');
    });

    /* ------------------------------- Content autosave ------------------------------ */
    const autosaveForm = $('[data-autosave-form]');
    if (autosaveForm) {
        const status = $('[data-autosave]');
        const preview = $(autosaveForm.dataset.previewFrame || '#content-preview');
        let timer = null;
        let revision = 0;
        let savedRevision = 0;
        let controller = null;
        let submitting = false;

        const setStatus = (text, state) => {
            if (!status) return;
            status.textContent = text;
            status.classList.remove('autosave--saving', 'autosave--saved', 'autosave--error');
            if (state) status.classList.add(`autosave--${state}`);
        };

        const markDirty = () => {
            revision += 1;
            setStatus('Unsaved changes');
            window.clearTimeout(timer);
            timer = window.setTimeout(save, 1000);
        };

        async function save() {
            window.clearTimeout(timer);
            if (revision === savedRevision) return;
            if (controller) controller.abort();
            controller = new AbortController();
            const requestedRevision = revision;
            setStatus('Saving…', 'saving');
            const formData = new FormData(autosaveForm);

            try {
                const response = await fetch(autosaveForm.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf(),
                    },
                    body: formData,
                    credentials: 'same-origin',
                    signal: controller.signal,
                });
                const payload = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const message = payload.message || (payload.errors && Object.values(payload.errors).flat()[0]) || 'Couldn’t save.';
                    throw new Error(message);
                }
                savedRevision = requestedRevision;
                if (revision !== requestedRevision) {
                    setStatus('Unsaved changes');
                    timer = window.setTimeout(save, 800);
                } else {
                    setStatus(`Saved · ${payload.saved_at || ''}`, 'saved');
                }
                if (preview && payload.preview_url) {
                    preview.src = payload.preview_url;
                }
            } catch (error) {
                if (error.name === 'AbortError') return;
                setStatus(error.message ? `Couldn’t save · ${error.message}` : 'Couldn’t save', 'error');
            }
        }

        autosaveForm.addEventListener('input', markDirty);
        autosaveForm.addEventListener('change', markDirty);

        autosaveForm.addEventListener('submit', () => {
            submitting = true;
            window.clearTimeout(timer);
            setStatus('Saving…', 'saving');
        });

        window.addEventListener('beforeunload', (event) => {
            if (!submitting && revision !== savedRevision) {
                event.preventDefault();
                event.returnValue = '';
            }
        });

        updateProjectCount(autosaveForm);
    }

    /* ----------------------------- Content preview sizes --------------------------- */
    $$('[data-preview-device]').forEach((button) => {
        button.addEventListener('click', () => {
            const group = button.closest('.device-toggle');
            const stage = $('[data-preview-stage]');
            if (!group || !stage) return;
            $$('button', group).forEach((item) => {
                item.classList.toggle('is-active', item === button);
                item.setAttribute('aria-pressed', String(item === button));
            });
            stage.classList.toggle('preview-frame--mobile', button.dataset.previewDevice === 'mobile');
        });
    });

    /* --------------------------------- Code editor -------------------------------- */
    const codeForm = $('[data-code-form]');
    if (codeForm) {
        const editors = {
            html: $('#code-html'),
            css: $('#code-css'),
            js: $('#code-js'),
        };
        const tabs = $$('[data-code-tab]');
        const panes = $$('[data-code-pane]');
        const preview = $(codeForm.dataset.previewFrame || '#code-preview');
        const status = $('[data-code-status]');
        const upload = $('[data-code-upload]');
        let active = 'html';
        let dirty = false;
        let revision = 0;
        let savedRevision = 0;
        let saveTimer = null;
        let previewTimer = null;
        let controller = null;

        const fontHref = $('link[href*="fonts.css"]')?.href || `${window.location.origin}/assets/css/fonts.css`;

        const setStatus = (text, state) => {
            if (!status) return;
            status.textContent = text;
            status.classList.remove('is-saved', 'is-saving', 'is-error');
            if (state) status.classList.add(`is-${state}`);
        };

        const formatKb = (value) => `${(new TextEncoder().encode(value).length / 1000).toFixed(1)} KB`;

        function updateCounts() {
            Object.keys(editors).forEach((key) => {
                const target = $(`[data-code-count="${key}"]`);
                if (target && editors[key]) target.textContent = formatKb(editors[key].value);
            });
        }

        function updateLineNumbers(key) {
            const pane = $(`[data-code-pane="${key}"]`);
            const numbers = pane ? $('[data-line-numbers]', pane) : null;
            if (!numbers || !editors[key]) return;
            const count = editors[key].value.split('\n').length;
            numbers.textContent = Array.from({ length: count }, (_, i) => i + 1).join('\n');
        }

        function resizeEditor(key) {
            const editor = editors[key];
            if (!editor || editor.closest('[data-code-pane]').hidden) return;
            editor.style.height = 'auto';
            editor.style.height = `${editor.scrollHeight}px`;
            updateLineNumbers(key);
        }

        function buildPreviewDocument(html, css, js) {
            const fontLink = `<link rel="stylesheet" href="${fontHref}">`;
            const styleTag = `<style>\n${css}\n</style>`;
            const scriptTag = `<script>\n${js}\n<\/script>`;

            if (/<html[\s>]/i.test(html)) {
                let doc = html;
                if (/<\/head>/i.test(doc)) {
                    doc = doc.replace(/<\/head>/i, `${fontLink}\n${styleTag}\n</head>`);
                } else if (/<body[\s>]/i.test(doc)) {
                    doc = doc.replace(/<body([\s>])/i, `<head>${fontLink}${styleTag}</head><body$1`);
                } else {
                    doc = `<head>${fontLink}${styleTag}</head>${doc}`;
                }
                if (scriptTag.trim() !== '<script></script>') {
                    doc = /<\/body>/i.test(doc) ? doc.replace(/<\/body>/i, `${scriptTag}\n</body>`) : doc + scriptTag;
                }
                return doc;
            }

            return `<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Preview</title>
${fontLink}
${styleTag}
</head>
<body>
${html}
${scriptTag}
</body>
</html>`;
        }

        function renderPreview() {
            if (!preview) return;
            const doc = buildPreviewDocument(
                editors.html?.value || '',
                editors.css?.value || '',
                editors.js?.value || ''
            );
            preview.removeAttribute('src');
            preview.srcdoc = doc;
        }

        function schedulePreview() {
            window.clearTimeout(previewTimer);
            previewTimer = window.setTimeout(renderPreview, 320);
        }

        function scheduleSave() {
            window.clearTimeout(saveTimer);
            saveTimer = window.setTimeout(() => saveCode(false), 1600);
        }

        function markDirty(key) {
            dirty = true;
            revision += 1;
            const tab = $(`[data-code-tab="${key}"]`);
            if (tab) tab.classList.add('is-dirty');
            setStatus('Unsaved changes');
            updateCounts();
            resizeEditor(key);
            schedulePreview();
            scheduleSave();
        }

        async function saveCode(manual) {
            window.clearTimeout(saveTimer);
            if (!dirty) {
                if (manual) setStatus('No changes to save', 'saved');
                return;
            }
            if (controller) controller.abort();
            controller = new AbortController();
            const requestedRevision = revision;
            setStatus(manual ? 'Saving…' : 'Autosaving…', 'saving');

            try {
                const response = await fetch(codeForm.action, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf(),
                    },
                    body: JSON.stringify({
                        html: editors.html?.value || '',
                        css: editors.css?.value || '',
                        js: editors.js?.value || '',
                    }),
                    credentials: 'same-origin',
                    signal: controller.signal,
                });
                const payload = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const detail = payload.message || (payload.errors && Object.values(payload.errors).flat()[0]) || 'Save failed.';
                    throw new Error(detail);
                }
                savedRevision = requestedRevision;
                if (revision === requestedRevision) {
                    dirty = false;
                    tabs.forEach((tab) => tab.classList.remove('is-dirty'));
                    setStatus(payload.saved_at ? `Saved · ${payload.saved_at}` : 'Saved', 'saved');
                } else {
                    setStatus('Unsaved changes');
                    scheduleSave();
                }
            } catch (error) {
                if (error.name === 'AbortError') return;
                setStatus(error.message || 'Save failed. Try again.', 'error');
            }
        }

        function activateTab(key) {
            active = key;
            tabs.forEach((tab) => {
                const selected = tab.dataset.codeTab === key;
                tab.classList.toggle('is-active', selected);
                tab.setAttribute('aria-selected', String(selected));
            });
            panes.forEach((pane) => {
                pane.hidden = pane.dataset.codePane !== key;
            });
            resizeEditor(key);
            if (editors[key]) editors[key].focus({ preventScroll: true });
        }

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => activateTab(tab.dataset.codeTab));
        });

        Object.keys(editors).forEach((key) => {
            const editor = editors[key];
            if (!editor) return;
            editor.addEventListener('input', () => markDirty(key));
            editor.addEventListener('keydown', (event) => {
                if (event.key === 'Tab') {
                    event.preventDefault();
                    const start = editor.selectionStart;
                    const end = editor.selectionEnd;
                    editor.value = `${editor.value.slice(0, start)}  ${editor.value.slice(end)}`;
                    editor.selectionStart = editor.selectionEnd = start + 2;
                    markDirty(key);
                }
            });
            resizeEditor(key);
        });

        codeForm.addEventListener('submit', (event) => {
            if (!window.fetch) return;
            event.preventDefault();
            saveCode(true);
        });

        document.addEventListener('keydown', (event) => {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 's') {
                if (document.activeElement && document.activeElement.closest('[data-code-form]')) {
                    event.preventDefault();
                    saveCode(true);
                }
            }
        });

        $$('[data-device]').forEach((button) => {
            button.addEventListener('click', () => {
                const group = button.closest('.device-toggle');
                const stage = $('[data-preview-stage]');
                if (!group || !stage) return;
                $$('button', group).forEach((item) => {
                    item.classList.toggle('is-active', item === button);
                    item.setAttribute('aria-pressed', String(item === button));
                });
                stage.classList.toggle('is-mobile', button.dataset.device === 'mobile');
            });
        });

        if (upload) {
            upload.addEventListener('change', () => {
                const file = upload.files && upload.files[0];
                if (!file) return;
                const data = new FormData();
                data.append('file', file);
                data.append('kind', 'project');
                setStatus('Uploading image…', 'saving');

                fetch(codeForm.dataset.uploadUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf(),
                    },
                    body: data,
                    credentials: 'same-origin',
                })
                    .then(async (response) => {
                        const payload = await response.json().catch(() => ({}));
                        if (!response.ok) throw new Error(payload.error || 'Upload failed.');
                        return payload;
                    })
                    .then((payload) => {
                        const editor = editors[active];
                        if (!editor) return;
                        const start = editor.selectionStart;
                        const end = editor.selectionEnd;
                        const insert = payload.url;
                        editor.value = `${editor.value.slice(0, start)}${insert}${editor.value.slice(end)}`;
                        editor.selectionStart = editor.selectionEnd = start + insert.length;
                        markDirty(active);
                        setStatus('Image URL inserted', 'saving');
                    })
                    .catch((error) => {
                        setStatus(error.message || 'Image upload failed.', 'error');
                    })
                    .finally(() => {
                        upload.value = '';
                    });
            });
        }

        window.addEventListener('beforeunload', (event) => {
            if (dirty) {
                event.preventDefault();
                event.returnValue = '';
            }
        });

        updateCounts();
        resizeEditor('html');
        renderPreview();
    }
})();
