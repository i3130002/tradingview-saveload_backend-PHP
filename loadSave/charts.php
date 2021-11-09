<?php

header('Content-Type: application/json; charset=utf-8');

function saveData($data)
{
  $objData = json_encode($data);
  $fp = fopen("savedCharts.json", "w");
  fwrite($fp, $objData);
  fclose($fp);
}

function loadData($containContent)
{
  $objData = file_get_contents("savedCharts.json") or "[]";
  $obj = json_decode($objData, true);
  $dataArray = array();
  foreach ($obj as $obj_) {
    $data = new stdClass();
    $data->timestamp = $obj_["timestamp"];
    $data->symbol = $obj_["symbol"];
    $data->resolution = $obj_["resolution"];
    $data->id = $obj_["id"];
    $data->name = $obj_["name"];
    if ($containContent)
      $data->content = $obj_["content"];
    $dataArray[] = $data;
  }
  return $dataArray;
}

function sendResponse($data, $id = null)
{
  $responseObj = new stdClass();
  $responseObj->status = "ok";
  if ($id !== null)
    $responseObj->id = $id;
  if ($data !== null)
    $responseObj->data = $data;
  die(json_encode($responseObj));
}

function loadChart()
{
  $objData = loadData(true);
  foreach ($objData as $obj) {
    if ($obj->id === intval($_GET["chart"])) {
      sendResponse($obj);
      return;
    }
  }
}

function listItems()
{
  $objData = loadData(false);
  sendResponse($objData);
}


function saveAs()
{
  $dataArray = loadData(true);
  foreach ($dataArray as &$data) {
    if ($data->id === intval($_GET["chart"])) {
      $data->timestamp = time();
      $data->symbol = $_POST["symbol"];
      $data->resolution = $_POST["resolution"];
      $data->name = $_POST["name"];
      $data->content = $_POST["content"];
      break;
    }
  }
  saveData($dataArray);
  sendResponse(null);
}

function save()
{
  $dataArray = loadData(true);
  $data = new stdClass();
  $data->timestamp = time();
  $data->symbol = $_POST["symbol"];
  $data->resolution = $_POST["resolution"];
  $data->id = time();
  $data->name = $_POST["name"];
  $data->content = $_POST["content"];
  $dataArray[] = $data;
  saveData($dataArray);
  sendResponse(null, $data->id);
}

function deleteChart()
{
  $objData = loadData(true);
  $newData = array();
  foreach ($objData as $obj_) {
    if ($obj_->id == $_GET["id"])
      continue;
    $newData[] = $obj_;
  }
  saveData($newData);
  sendResponse(null);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_GET["chart"]))
    saveAs();
  else
    save();
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  deleteChart();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if (isset($_GET["chart"]))
    loadChart();
  else
    listItems();
}
