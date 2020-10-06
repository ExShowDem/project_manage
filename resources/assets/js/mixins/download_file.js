export default function downloadFile(opts, filename = '') {
  opts = {...opts, responseType: 'blob', dontDisableScreen: true};
  axios(opts)
    .then(result => {
      filename = filename || result.headers['content-disposition'].split('"')[1];
      let blob = new Blob([result.data], {
        type: result.headers['content-type'],
      });
      if (window.navigator && window.navigator.msSaveOrOpenBlob) {
        window.navigator.msSaveOrOpenBlob(blob, filename);
      } else {
        const link = document.createElement('a');
        document.body.appendChild(link);
        link.style = 'display: none';
        link.href = window.URL.createObjectURL(blob);
        link.download = filename;
        link.click();
      }
    })
    .catch();
}
