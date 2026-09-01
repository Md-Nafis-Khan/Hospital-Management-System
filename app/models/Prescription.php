<?php

function countPatientPrescriptions($db, $patientId)
{
    $stmt = mysqli_prepare($db, "SELECT COUNT(*) AS total FROM prescriptions WHERE patient_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $patientId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return (int) $row['total'];
}
