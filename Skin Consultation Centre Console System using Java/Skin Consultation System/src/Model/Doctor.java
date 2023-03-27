package Model;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.TreeMap;

public class Doctor extends Person {
    private String medicalLicenceNumber;
    private String specialization;
    private TreeMap<String, Integer> availability;

    public Doctor(String firstName,
                  String surName,
                  LocalDate dateOfBirth,
                  String phoneNumber,
                  String medicalLicenceNumber,
                  String specialization,
                  TreeMap<String, Integer>availability){
        super(firstName, surName, dateOfBirth, phoneNumber);
        setAvailability(availability);
        this.medicalLicenceNumber = medicalLicenceNumber;
        this.specialization = specialization;
        this.availability = availability;
    }

    public static List<Doctor> LoadDoctors() {
        return null;
    }

    public String getMedicalLicenceNumber() {
        return medicalLicenceNumber;
    }

    public void setMedicalLicenceNumber(String medicalLicenceNumber) {
        this.medicalLicenceNumber = medicalLicenceNumber;
    }

    public String getSpecialisation() {
        return specialization;
    }

    public void setSpecialisation(String specialisation) {
        this.specialization = specialisation;
    }

    public TreeMap<String, Integer> getAvailability() {
        return availability;
    }

    public void setAvailability(TreeMap<String, Integer> availability) {
        for (int i = 0; i < 31; i++) {
            //available - 0
            //unavailable - 1
            availability.put(Integer.toString(i+1), 0);
        }
        
        this.availability = availability;
    }

    @Override
    public String toString() {
        return super.toString()+
                " MedicalLicenceNumber='" + medicalLicenceNumber + '\'' +
                ",Specialization='" + specialization + '\'' ;
    }
}

