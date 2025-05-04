import javax.swing.*;
import java.awt.*;
import java.awt.event.*;

public class MainApp extends JFrame {

    public MainApp() {
        // Set the title of the window
        super("Inventory & Sales System");

        // Maximize on launch
        setExtendedState(JFrame.MAXIMIZED_BOTH);
        setDefaultCloseOperation(EXIT_ON_CLOSE);

        // Create menu bar
        JMenuBar menuBar = new JMenuBar();

        // ===== Menu =====
        JMenu menu = new JMenu("Menu");

        JMenuItem stockManagement = new JMenuItem("Stock Management");
        JMenu salesInvoicing = new JMenu("Sales and Invoicing");

        JMenuItem invoices = new JMenuItem("Invoices");
        JMenuItem clients = new JMenuItem("Clients");
        salesInvoicing.add(invoices);
        salesInvoicing.add(clients);

        JSeparator separator1 = new JSeparator();
        JMenu systemAdmin = new JMenu("System Administration");

        JMenuItem userManagement = new JMenuItem("User Management");
        systemAdmin.add(userManagement);

        JSeparator separator2 = new JSeparator();
        JMenuItem exit = new JMenuItem("Exit");
        exit.addActionListener(e -> System.exit(0));

        // Add items to "Menu"
        menu.add(stockManagement);
        menu.add(salesInvoicing);
        menu.addSeparator();
        menu.add(systemAdmin);
        menu.addSeparator();
        menu.add(exit);

        // ===== Help =====
        JMenu help = new JMenu("Help");
        JMenuItem userGuide = new JMenuItem("User Guide");
        JMenuItem about = new JMenuItem("About");

        userGuide.addActionListener(e -> showHtmlDialog("User Guide", "<h1>User Guide</h1><p>This is the user guide.</p>"));
        about.addActionListener(e -> showHtmlDialog("About", "<h1>About</h1><p>Version 1.0 - Created by You</p>"));

        help.add(userGuide);
        help.add(about);

        // Add menus to bar
        menuBar.add(menu);
        menuBar.add(help);

        // Set menu bar
        setJMenuBar(menuBar);

        // Set content pane (optional)
        getContentPane().setLayout(new BorderLayout());
        JLabel welcome = new JLabel("<html><h1>Welcome to the Inventory & Sales System</h1></html>", SwingConstants.CENTER);
        getContentPane().add(welcome, BorderLayout.CENTER);

        // Show window
        setVisible(true);
    }

    private void showHtmlDialog(String title, String htmlContent) {
        JOptionPane.showMessageDialog(this,
                new JLabel(htmlContent),
                title,
                JOptionPane.INFORMATION_MESSAGE);
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(MainApp::new);
    }
}