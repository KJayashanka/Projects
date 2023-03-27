package Model;
import java.time.LocalDate;

public class Patient extends Person {
    private String patientId;

    public Patient(String firstName, String surName, LocalDate dateOfBirth, String phoneNumber, String patientId) {
        super(firstName, surName, dateOfBirth, phoneNumber);
        this.patientId = patientId;

    }

    public String getPatientId() {

        return patientId;
    }

    public void setPatientId(String patientId) {

        this.patientId = patientId;
    }
}
