export default function getJQuery () {
    let j;

    if (typeof window.jQuery == 'function') {
        j = window.jQuery;
        console.log('using existing jQuery');
    } else {
        console.log('no jQuery available!');
    }

    return j;
};