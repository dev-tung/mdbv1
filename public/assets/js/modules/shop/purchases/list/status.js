export const Status = {

    init(url) {
        this.url = url;

        document.addEventListener('change', async (e) => {

            const el = e.target;

            if (el.dataset.action !== 'status') return;

            const form = new FormData();
            form.append('id', el.dataset.id);
            form.append('status', el.value);

            await fetch(this.url, {
                method: 'POST',
                body: form
            });

            Table.load(1);
        });
    }
};