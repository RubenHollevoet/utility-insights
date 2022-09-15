<!doctype html>
<html lang="en">
<head>
    <?php include "_partials/headConfig.html";?>
    <title>Statistics</title>
</head>
<body>

<?php include "_partials/header.html"; ?>

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
                    <tbody id="swarm-table-body">
                    </tbody>
                </table>
                <span class="btn btn-outline-success" id="swarm-add" data-bs-toggle="modal" data-bs-target="#swarmAddModal">Add</span>
            </div>
        </div>

        <div class="row my-4">
            <h2>Actions</h2>
            <div class="col-12">
                <span class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateModal">Check for updates</span>
                <span class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rebootModal">Reboot</span>
            </div>
        </div>

        <!--#################################-->
        <!--MODALS -->
        <!--#################################-->
        <!--settings modal-->
        <div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update settings</h5>
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
                            This setting requires a reboot to take effect
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="settings-submit">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!--reboot modal-->
        <div class="modal fade" id="rebootModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure?</h5>
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

        <!--swarm add modal-->
        <div class="modal fade" id="swarmAddModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add participant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="participant-add-name" class="col-form-label">Name:</label>
                                <input type="text" class="form-control" id="participant-add-name">
                            </div>
                            <div class="mb-3">
                                <label for="participant-add-url" class="col-form-label">URL:</label>
                                <input type="text" class="form-control" id="participant-add-url">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="participant-submit">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!--participantRemoveModal-->
        <div class="modal fade" id="participantRemoveModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            The participant will be removed permanently from the swarm.
                            <br>
                            Name: <b id="participant-remove-name"></b>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <span id="participant-remove-id" class="d-none"></span>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="participant-remove-submit">Remove</button>
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

            function loadSwarmTable(settings) {

                $tableBody = document.getElementById('swarm-table-body');
                $tableBody.innerHTML = '';
                settings.forEach((x, i) => {
                    const $tr = document.createElement('tr');

                    const $name = document.createElement('td');
                    $name.textContent = x['name'];

                    const $url = document.createElement('td');
                    $url.textContent = x['url'];

                    const $actions = document.createElement('td');
                    const $remove = document.createElement('span');

                    $remove.innerHTML = 'remove';
                    $remove.classList.add('btn', 'btn-outline-danger');
                    $remove.dataset.bsToggle="modal";
                    $remove.dataset.bsTarget="#participantRemoveModal";
                    $remove.dataset.bsId=x['id'];
                    $remove.dataset.bsName=x['name'];
                    $actions.appendChild($remove);

                    $tr.appendChild($name);
                    $tr.appendChild($url);
                    $tr.appendChild($actions);

                    $tableBody.appendChild($tr);
                });
            }

            function updateSettings() {
                fetch('/api/getSettings.php')
                    .then(response => response.json())
                    .then(data => {
                        loadSettingsTable(data)
                    });
            }

            function updateSwarm() {
                fetch('/api/getParticipants.php')
                    .then(response => response.json())
                    .then(data => {
                        loadSwarmTable(data)
                    });
            }

            //setting submit
            const settingsModal = document.getElementById('settingsModal')
            const settingsKeyInput = settingsModal.querySelector('#settings-key');
            const settingsValueInput = settingsModal.querySelector('#settings-value');
            //
            settingsModal.addEventListener('show.bs.modal', function (event) {
                settingsKeyInput.value = event.relatedTarget.dataset.bsKey;
                settingsValueInput.value = event.relatedTarget.dataset.bsValue;
            });
            //
            const settingsSubmit = document.getElementById('settings-submit');
            settingsSubmit.addEventListener('click', function (e) {
                fetch('/api/updateSetting.php?key=' + settingsKeyInput.value + '&value=' + settingsValueInput.value)
                    .then(response => response.json())
                    .then(data => {
                        updateSettings();
                        bootstrap.Modal.getInstance(settingsModal).hide();
                    });
            });

            // reboot
            document.getElementById('reboot-submit').addEventListener('click', function (e) {
                fetch('/api/reboot.php', {});
            });

            // participant add
            const swarmAddModal = document.getElementById('swarmAddModal')
            const participantSubmit = document.getElementById('participant-submit');
            //
            swarmAddModal.addEventListener('show.bs.modal', function (event) {
                document.getElementById('participant-add-name').value = '';
                document.getElementById('participant-add-url').value = '';
            });
            //
            participantSubmit.addEventListener('click', function (e) {
                fetch('/api/addParticipant.php?name=' + swarmAddModal.querySelector('#participant-add-name').value + '&url=' + swarmAddModal.querySelector('#participant-add-url').value)
                    .then(response => response.json())
                    .then(data => {
                        updateSwarm();
                        bootstrap.Modal.getInstance(swarmAddModal).hide();
                    });
            });

            // remove participant
            const participantRemoveModal = document.getElementById('participantRemoveModal');
            const participantRemoveSubmit = document.getElementById('participant-remove-submit');
            //
            console.log('participantRemoveModal', participantRemoveModal);
            participantRemoveModal.addEventListener('show.bs.modal', function (event) {
                console.log( event.relatedTarget, event.relatedTarget.dataset.bsName);
                document.getElementById('participant-remove-name').innerHTML = event.relatedTarget.dataset.bsName;
                document.getElementById('participant-remove-id').innerHTML = event.relatedTarget.dataset.bsId;
            });
            //
            participantRemoveSubmit.addEventListener('click', function (e) {
                fetch('/api/removeParticipant.php?id=' + participantRemoveModal.querySelector('#participant-remove-id').innerHTML)
                    .then(response => response.json())
                    .then(data => {
                        updateSwarm();
                        bootstrap.Modal.getInstance(participantRemoveModal).hide();
                    });
            });

            updateSettings();
            updateSwarm();
        </script>
    </div>
</div>


<?php
include "_partials/footer.html";
?>
</body>
</html>