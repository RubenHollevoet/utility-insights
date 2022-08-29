<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Statistics</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>

<?php
      include "_partials/header.html";
    ?>

<div class="content">
    <div class="container mt-4">
        <h1>Settings</h1>

        <div class="row my-4">
            <h2>Configuratie</h2>
            <div class="col-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">key</th>
                        <th scope="col">value</th>
                        <th scope="col">actions</th>
                    </tr>
                    </thead>
                    <tbody id="settings-table-body">
                    </tbody>
                </table>
            </div>
        </div>


        <div class="row my-4">
            <h2>Swarm</h2>
            <div class="col-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">url</th>
                        <th scope="col">actions</th>
                    </tr>
                    </thead>
                    <tbody class="swarm-table-body">
                    </tbody>
                </table>


                <span class="btn btn-outline-success">Add</span>
            </div>
        </div>

        <div class="row my-4">
            <h2>Actions</h2>
            <div class="col-12">
                <span class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rebootModal">Reboot</span>
            </div>
        </div>

        <!--#################################-->
        <!--MODALS -->
        <!--#################################-->
        <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update settings</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="settings-key" class="col-form-label">Key:</label>
                                <input type="text" class="form-control" id="settings-key" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="settings-value" class="col-form-label">Value:</label>
                                <input type="text" class="form-control" id="settings-value">
                            </div>
                        </form>
                        <div class="alert alert-danger" role="alert">
                            This setting requires a reboot to take affect
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="settings-submit">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="rebootModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            The device will reboot and you will lose connection for several minutes. Statistics won't be recorded and others won't be able to relate on your data.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="reboot-submit">Reboot</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function loadSettingsTable(settings) {

                $tableBody = document.getElementById('settings-table-body');
                $tableBody.innerHTML = '';
                settings.forEach((x, i) => {
                    const $tr = document.createElement('tr');

                    const $key = document.createElement('td');
                    $key.textContent = x['key'];

                    const $value = document.createElement('td');
                    $value.textContent = x['value'];

                    const $actions = document.createElement('td');
                    const $edit = document.createElement('span');

                    $edit.innerHTML = 'edit';
                    $edit.classList.add('btn', 'btn-outline-primary');
                    $edit.dataset.bsToggle="modal";
                    $edit.dataset.bsTarget="#settingsModal";
                    $edit.dataset.bsKey=x['key'];
                    $edit.dataset.bsValue=x['value'];
                    $actions.appendChild($edit);

                    $tr.appendChild($key);
                    $tr.appendChild($value);
                    $tr.appendChild($actions);

                    $tableBody.appendChild($tr);
                });
            }

            function updateSettings() {
                fetch('/api/readSettings.php')
                    .then(response => response.json())
                    .then(data => {
                        loadSettingsTable(data)
                    });
            }

            const settingsModal = document.getElementById('settingsModal')
            const settingsKeyInput = settingsModal.querySelector('#settings-key');
            const settingsValueInput = settingsModal.querySelector('#settings-value');


            settingsModal.addEventListener('show.bs.modal', function (event) {
                settingsKeyInput.value = event.relatedTarget.dataset.bsKey;
                settingsValueInput.value = event.relatedTarget.dataset.bsValue;
            });

            const settingsSubmit = document.getElementById('settings-submit');
            settingsSubmit.addEventListener('click', function (e) {
                fetch('/api/updateSetting.php?key=' + settingsKeyInput.value + '&value=' + settingsValueInput.value)
                    .then(response => response.json())
                    .then(data => {
                        updateSettings();
                        bootstrap.Modal.getInstance(settingsModal).hide();
                    });
            });

            document.getElementById('reboot-submit').addEventListener('click', function (e) {
                fetch('/api/reboot.php', {});
            });

            updateSettings();
        </script>
    </div>
</div>


<?php
include "_partials/footer.html";
?>
</body>
</html>