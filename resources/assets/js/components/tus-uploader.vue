<template>
     <div>
          <div class="page-header">
               <h1>Upload your file</h1>
          </div>

          <div class="input-group m-b-20">
               <input type="file" class="form-control" accept="video/*" id="file-input" @change="chooseFile" :disabled="isUploading">
               <div class="input-group-btn">
                    <button type="button" class="btn btn-default" @click="upload" :disabled="isUploading">Upload</button>
               </div>
          </div><!-- /input-group -->

          <div class="progress" v-if="isUploading">
               <div class="progress-bar progress-bar-success" role="progressbar" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100" :style="'width: ' + progress + '%'">{{ progress }}%</div>
          </div>

          <div v-if="isUploaded" class="alert alert-success">
               <h4>Your file were successfully uploaded.</h4>
          </div>

          <div v-if="error" class="alert alert-danger">
               <h4>Error while uploading.</h4>
               <p>Message: {{ error }}</p>
          </div>
     </div>
</template>

<script>
     export default {
          name: "tus-uploader",
          data: function () {
               return {
                    isUploading: false,
                    isUploaded: false,
                    error: null,
                    progress: 0,
                    fileSelected: null,
                    fileUrl: null,
               };
          },
          methods: {
               chooseFile(event) {
                    this.fileSelected = event.target.files[0];
                    this.isUploaded = false;
                    this.isUploading = false;
               },
               upload(event) {
                    this.isUploading = true;

                    let vm = this;

                    // Create a new tus upload
                    var upload = new tus.Upload(vm.fileSelected, {
                         endpoint: "/tus",
                         retryDelays: [0], // , 1000, 3000, 5000
                         metadata: {
                              filename: vm.fileSelected.name,
                              filetype: vm.fileSelected.type
                         },
                         onError: function (error) {
                              console.log("Failed because: " + error)
                              vm.isUploading = false;
                              vm.error = error;
                         },
                         onProgress: function (bytesUploaded, bytesTotal) {
                              var percentage = (bytesUploaded / bytesTotal * 100).toFixed(2)
                              // console.log(bytesUploaded, bytesTotal, percentage + "%")

                              vm.progress = percentage;
                         },
                         onSuccess: function () {
                              // console.log("Download %s from %s", upload.file.name, upload.url)
                              vm.isUploading = false;
                              vm.isUploaded = true;
                              vm.fileUrl = upload.url;
                         }
                    });

                    // Start the upload
                    upload.start()
               },
          }
     }
</script>

<style scoped>
     .progress {
          margin-top: 20px;
     }
     .m-b-20 {
          margin-bottom: 20px;
     }
</style>