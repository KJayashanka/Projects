package Model;
import java.io.Serializable;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.TreeMap;

public class Person implements Serializable {
    private String firstName;
    private String surname;
    private LocalDate dateOfbirth;
    private String phoneNumber;
    private String specialisation;
    private String medicalLicenceNumber;
    private TreeMap<String, Integer> availability;

    public Person(String firstName, String surname, LocalDate dateOfbirth, String phoneNumber) {
        this.firstName = firstName;
        this.surname = surname;
        this.dateOfbirth = dateOfbirth;
        this.phoneNumber = phoneNumber;
    }

    public String getFirstName() {
        return firstName;
    }

    public void setFirstName(String firstName) {
        this.firstName = firstName;
    }

    public String getSurname() {
        return surname;
    }

    public void setSurNname(String surname) {
        this.surname = surname;
    }

    public LocalDate getDateOfBirth() {
        return dateOfbirth;
    }

    public void setDateOfBirth(LocalDate dateOfbirth) {
        this.dateOfbirth = dateOfbirth;
    }

    public String getPhoneNumber() {
        return phoneNumber;
    }

    public void setPhoneNumber(String phoneNumber) {
        this.phoneNumber = phoneNumber;
    }

    @Override
    public String toString() {
        return
                "FirstName='" + firstName + '\'' +
                ", SurName='" + surname + '\'' +
                ", Date of Birth=" + dateOfbirth +
                ", PhoneNumber='" + phoneNumber + '\'';
    }

    public String getSpecialisation() {
        return specialisation;
    }

    public String getMedicalLicenceNumber() {
        return medicalLicenceNumber;
    }

    public TreeMap<String, Integer> getAvailability() {
        return availability;
    }
}
