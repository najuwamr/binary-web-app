import { getDocument, GlobalWorkerOptions } from 'pdfjs-dist';
import pdfWorkerUrl from 'pdfjs-dist/build/pdf.worker.min.mjs?url';
import { PageFlip } from 'page-flip';
import 'page-flip/src/Style/stPageFlip.css';

GlobalWorkerOptions.workerSrc = pdfWorkerUrl;

const initializeFlipbook = async () => {
  const bookElement = document.querySelector('[data-pdf-flipbook]');
  const errorElement = document.querySelector('[data-flip-error]');
  const pageLabel = document.querySelector('[data-flip-page-label]');
  const previousButton = document.querySelector('[data-flip-prev]');
  const nextButton = document.querySelector('[data-flip-next]');

  if (!bookElement || !errorElement || !pageLabel || !previousButton || !nextButton) {
    return;
  }

  const pdfUrl = bookElement.dataset.pdfUrl;
  let pageFlip;

  const updateControls = (currentPage, pageCount) => {
    pageLabel.textContent = `Halaman ${currentPage + 1} dari ${pageCount}`;
    previousButton.disabled = currentPage <= 0;
    nextButton.disabled = currentPage >= pageCount - 1;
  };

  try {
    if (!pdfUrl) {
      throw new Error('PDF URL tidak ditemukan pada elemen flipbook.');
    }

    const loadingTask = getDocument(pdfUrl);
    const pdf = await loadingTask.promise;
    const pageElements = [];

    for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
      const pdfPage = await pdf.getPage(pageNumber);
      const baseViewport = pdfPage.getViewport({ scale: 1 });
      const scale = Math.min(1.2, 640 / baseViewport.width, 900 / baseViewport.height);
      const viewport = pdfPage.getViewport({ scale });
      const canvas = document.createElement('canvas');
      const context = canvas.getContext('2d');

      if (!context) {
        throw new Error('Browser tidak mendukung rendering halaman PDF.');
      }

      canvas.width = Math.ceil(viewport.width);
      canvas.height = Math.ceil(viewport.height);

      await pdfPage.render({ canvas, canvasContext: context, viewport }).promise;

      const page = document.createElement('div');
      page.className = 'bulletin-page';
      page.setAttribute('data-density', pageNumber === 1 || pageNumber === pdf.numPages ? 'hard' : 'soft');

      const image = document.createElement('img');
      image.src = canvas.toDataURL('image/jpeg', 0.86);
      image.alt = `Halaman ${pageNumber}`;
      image.className = 'bulletin-page-image';
      page.append(image);
      pageElements.push(page);

      canvas.width = 0;
      canvas.height = 0;
      pdfPage.cleanup();
    }

    bookElement.replaceChildren(...pageElements);
    pageFlip = new PageFlip(bookElement, {
      width: 420,
      height: 594,
      size: 'stretch',
      minWidth: 280,
      maxWidth: 520,
      minHeight: 396,
      maxHeight: 740,
      showCover: true,
      usePortrait: true,
      drawShadow: true,
      maxShadowOpacity: 0.35,
      mobileScrollSupport: true,
    });

    pageFlip.on('flip', (event) => {
      updateControls(Number(event.data), pdf.numPages);
    });
    pageFlip.loadFromHTML(pageElements);
    updateControls(pageFlip.getCurrentPageIndex(), pdf.numPages);

    previousButton.addEventListener('click', () => pageFlip.flipPrev());
    nextButton.addEventListener('click', () => pageFlip.flipNext());

    window.addEventListener('beforeunload', () => pageFlip.destroy(), { once: true });
  } catch (error) {
    console.error('Unable to render bulletin PDF.', error);
    bookElement.classList.add('hidden');
    errorElement.textContent = 'PDF tidak dapat dimuat. Silakan gunakan tombol Buka PDF untuk mencoba membukanya langsung.';
    errorElement.hidden = false;
    pageLabel.textContent = 'Gagal memuat PDF';
  }
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeFlipbook, { once: true });
} else {
  initializeFlipbook();
}
