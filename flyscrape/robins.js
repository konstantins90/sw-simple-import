import { download } from "flyscrape/http";

export const config = {
    urls: [
        "https://robins.ru/catalog/knigi-s-okoshkami/knizhki-panoramki-belosnezhka/#gallery-1"
    ],

    // Cache request for later.
    // cache: "file",

    // Enable JavaScript rendering.
    // browser: false,
    // headless: false,

    // Follow pagination 5 times.
    // depth: 5,
    // follow: ["a.morelink[href]"],
}

export default function ({ doc, absoluteURL }) {
    const link = doc.find("#slider .slides li:first-child a");
    const items = doc.find(".product-pages-info-list li");

    let isbn;
    items.map((item) => {
        const name = item.find("span:first-child");
        const value = item.find("span:last-child");

        if (name.text() == 'ISBN') {
            isbn = value.text();
        }
    })

    const pictureURL = absoluteURL(link.attr('href'));

    download(pictureURL, `book_images/${isbn}.jpg`)

    return {
        link: "https://robins.ru" + link.attr('href'),
        isbn: isbn,
    }
}
