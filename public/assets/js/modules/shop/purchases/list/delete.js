export const Delete = {

    init(url) {
        this.url = url;

        document.addEventListener('click', async (e) => {

            const el = e.target.closest('[data-action="delete"]');
            if (!el) return;

            if (!confirm('Bạn có chắc muốn xóa?')) return;

            const form = new FormData();
            form.append('id', el.dataset.id);

            await fetch(this.url, {
                method: 'POST',
                body: form
            });

            Table.load(1);
        });
    }
};